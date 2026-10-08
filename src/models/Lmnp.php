<?php
declare(strict_types=1);

/**
 * Moteur de comptabilité LMNP (Loueur Meublé Non Professionnel).
 *
 * Calcule, pour un bien et une année :
 *  - les recettes encaissées (loyers + charges) — base de caisse,
 *  - les charges déductibles courantes,
 *  - les amortissements (bâti hors terrain, mobilier, travaux),
 *  - le résultat au régime RÉEL (l'amortissement ne peut pas créer de déficit),
 *  - la base imposable au régime MICRO-BIC (abattement 50 %).
 *
 * ⚠️ Outil d'aide à la gestion, ne remplace pas un expert-comptable.
 */
class Lmnp
{
    /** Seuil de recettes micro-BIC meublé (2024). */
    public const MICRO_BIC_CEILING = 77700;
    public const MICRO_BIC_ABATTEMENT = 0.50;

    /** Recettes encaissées sur l'année (base de caisse). */
    public static function recettes(int $propertyId, int $year): float
    {
        $rows = Database::all(
            "SELECT rp.amount_paid, rp.paid_date, rp.period_year
             FROM rent_payments rp JOIN leases l ON l.id = rp.lease_id
             WHERE l.property_id = ? AND rp.status = 'paid'",
            [$propertyId]
        );
        $sum = 0.0;
        foreach ($rows as $r) {
            $y = $r['paid_date'] ? (int) substr($r['paid_date'], 0, 4) : (int) $r['period_year'];
            if ($y === $year) $sum += (float) $r['amount_paid'];
        }
        return $sum;
    }

    /** 1er jour du mois de la première échéance : date saisie, sinon le mois qui suit l'achat. */
    public static function loanStart(array $p): ?DateTime
    {
        if (!empty($p['loan_start_date'])) return new DateTime(substr($p['loan_start_date'], 0, 7) . '-01');
        if (empty($p['purchase_date'])) return null;
        return (new DateTime(substr($p['purchase_date'], 0, 7) . '-01'))->modify('+1 month');
    }

    /**
     * Échéances payées sur une année (tableau d'amortissement à mensualité constante) :
     * ['payments' => total versé, 'interest' => intérêts, 'months' => nombre d'échéances].
     * $untilMonth limite le calcul aux mois 1..$untilMonth (année en cours, à date).
     */
    public static function loanForYear(array $p, int $year, int $untilMonth = 12): array
    {
        $out = ['payments' => 0.0, 'interest' => 0.0, 'months' => 0];
        $amount  = (float) $p['loan_amount'];
        $monthly = (float) $p['loan_monthly'];
        $rate    = (float) $p['loan_rate'] / 100 / 12;
        $start   = self::loanStart($p);
        if ($amount <= 0 || $monthly <= 0 || !$start) return $out;
        $maxMonths = (int) $p['loan_duration_months'] ?: 600;

        $balance = $amount;
        $y = (int) $start->format('Y'); $m = (int) $start->format('n');
        for ($i = 0; $i < $maxMonths && $balance > 0.01; $i++) {
            if ($y > $year || ($y === $year && $m > $untilMonth)) break;
            $interest  = $balance * $rate;
            $payment   = min($monthly, $balance + $interest);
            if ($payment - $interest <= 0) break; // mensualité insuffisante
            if ($y === $year) {
                $out['payments'] += $payment;
                $out['interest'] += $interest;
                $out['months']++;
            }
            $balance -= $payment - $interest;
            if (++$m > 12) { $m = 1; $y++; }
        }
        $out['payments'] = round($out['payments'], 2);
        $out['interest'] = round($out['interest'], 2);
        return $out;
    }

    /** Intérêts d'emprunt payés durant l'année. */
    public static function loanInterestForYear(array $p, int $year): float
    {
        return self::loanForYear($p, $year)['interest'];
    }

    /** Bases amortissables. */
    public static function amortBases(array $p): array
    {
        $land = (float) ($p['land_share_pct'] ?? 15) / 100;
        $buildingBase = ((float)$p['purchase_price'] + (float)$p['notary_fees'] + (float)$p['agency_fees'])
            * (1 - $land);
        $furnitureBase = (float) $p['other_costs']
            + Expense::totalByCategories((int)$p['id'], Expense::AMORT_FURNITURE);
        $worksBase = (float) $p['works_cost']
            + Expense::totalByCategories((int)$p['id'], Expense::AMORT_WORKS);
        return [
            'building'  => $buildingBase,
            'furniture' => $furnitureBase,
            'works'     => $worksBase,
        ];
    }

    /** Calcul fiscal complet pour un bien et une année. */
    public static function compute(array $p, int $year): array
    {
        $recettes = self::recettes((int)$p['id'], $year);

        // Charges déductibles courantes
        $mgmt = $recettes * ((float)$p['mgmt_fees_pct'] / 100);
        $interest = self::loanInterestForYear($p, $year);
        $deductibleOneOff = Expense::totalByCategories((int)$p['id'], Expense::DEDUCTIBLE, $year);
        // Montants réels de l'année (appels de charges, avis de taxe foncière…) s'ils sont saisis,
        // sinon estimations de la fiche du bien.
        $real = PropertyCost::totals((int)$p['id'], $year);
        // Année d'acquisition : estimations annuelles proratisées à partir de la date d'achat.
        $ratio = 1.0;
        if (!empty($p['purchase_date']) && (int) substr($p['purchase_date'], 0, 4) === $year) {
            $yearDays = (int) date('z', mktime(0, 0, 0, 12, 31, $year)) + 1;
            $ratio = ((int) date('z', mktime(0, 0, 0, 12, 31, $year)) - (int) date('z', strtotime($p['purchase_date'])) + 1) / $yearDays;
        } elseif (!empty($p['purchase_date']) && (int) substr($p['purchase_date'], 0, 4) > $year) {
            $ratio = 0.0;
        }
        $charges = [
            'taxe_fonciere' => $real['taxe_fonciere']['amount'] ?? round((float)$p['property_tax'] * $ratio, 2),
            'assurance'     => $real['assurance']['amount'] ?? round((float)$p['insurance_year'] * $ratio, 2),
            'charges_copro' => $real['copro']['amount'] ?? round((float)$p['charges_year'] * $ratio, 2),
            'gestion'       => $mgmt,
            'interets'      => $interest,
            'comptable'     => (float)($p['accountant_fees'] ?? 0),
            'autres'        => $deductibleOneOff + ($real['autre']['amount'] ?? 0.0),
        ];
        $chargesTotal = array_sum($charges);

        // Amortissements
        $bases = self::amortBases($p);
        $yb = max(1, (int)($p['amort_years_building'] ?? 30));
        $yf = max(1, (int)($p['amort_years_furniture'] ?? 7));
        $yw = max(1, (int)($p['amort_years_works'] ?? 10));
        $amort = [
            'building'  => $bases['building']  / $yb,
            'furniture' => $bases['furniture'] / $yf,
            'works'     => $bases['works']     / $yw,
        ];
        $amortTotal = array_sum($amort);

        // Régime RÉEL : l'amortissement ne peut pas créer/aggraver un déficit.
        $resultBeforeAmort = $recettes - $chargesTotal;
        $amortUsed  = max(0.0, min($amortTotal, $resultBeforeAmort));
        $amortCarry = $amortTotal - $amortUsed; // reportable sans limite de durée
        $resultReel = $resultBeforeAmort - $amortUsed;

        // Régime MICRO-BIC : abattement forfaitaire 50 %
        $microBase = $recettes * (1 - self::MICRO_BIC_ABATTEMENT);
        $microEligible = $recettes <= self::MICRO_BIC_CEILING;

        return [
            'year'              => $year,
            'recettes'          => $recettes,
            'charges'           => $charges,
            'charges_total'     => $chargesTotal,
            'amort_bases'       => $bases,
            'amort'             => $amort,
            'amort_total'       => $amortTotal,
            'amort_used'        => $amortUsed,
            'amort_carry'       => $amortCarry,
            'result_before_amort' => $resultBeforeAmort,
            'result_reel'       => $resultReel,
            'micro_base'        => $microBase,
            'micro_eligible'    => $microEligible,
            'best'              => $resultReel <= $microBase ? 'reel' : 'micro',
        ];
    }
}
