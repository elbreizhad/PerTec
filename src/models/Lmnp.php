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

    /**
     * Intérêts d'emprunt payés durant l'année (tableau d'amortissement
     * calculé à partir de la date d'achat, du capital, du taux et de la mensualité).
     */
    public static function loanInterestForYear(array $p, int $year): float
    {
        $amount  = (float) $p['loan_amount'];
        $monthly = (float) $p['loan_monthly'];
        $rate    = (float) $p['loan_rate'] / 100 / 12;
        if ($amount <= 0 || $monthly <= 0 || $rate <= 0 || empty($p['purchase_date'])) {
            return 0.0;
        }
        $startYear  = (int) substr($p['purchase_date'], 0, 4);
        $startMonth = (int) substr($p['purchase_date'], 5, 2) ?: 1;
        $maxMonths  = (int) $p['loan_duration_months'] ?: 600;

        $balance = $amount;
        $y = $startYear; $m = $startMonth;
        $interestYear = 0.0;
        for ($i = 0; $i < $maxMonths && $balance > 0.01; $i++) {
            $interest  = $balance * $rate;
            $principal = $monthly - $interest;
            if ($principal <= 0) break; // mensualité insuffisante
            if ($y === $year) $interestYear += $interest;
            $balance -= $principal;
            if (++$m > 12) { $m = 1; $y++; }
            if ($y > $year) break;
        }
        return round($interestYear, 2);
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
        $charges = [
            'taxe_fonciere' => (float)$p['property_tax'],
            'assurance'     => (float)$p['insurance_year'],
            'charges_copro' => (float)$p['charges_year'],
            'gestion'       => $mgmt,
            'interets'      => $interest,
            'comptable'     => (float)($p['accountant_fees'] ?? 0),
            'autres'        => $deductibleOneOff,
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
