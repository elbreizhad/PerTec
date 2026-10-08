<?php
declare(strict_types=1);

/**
 * Bilan annuel d'un bien (trésorerie réelle, base encaissements/décaissements) :
 * loyers et provisions encaissés, charges et impôts réels, intérêts d'emprunt,
 * dépenses de l'année, résultat, cash-flow et régularisation des charges
 * locatives. Les montants réels saisis (PropertyCost) remplacent les
 * estimations de la fiche du bien ; à défaut, l'estimation est utilisée et signalée.
 *
 * ⚠️ Outil d'aide à la gestion, ne remplace pas un expert-comptable.
 */
class Bilan
{
    /** Date d'arrêté par défaut : aujourd'hui pour l'année en cours, sinon le 31/12. */
    public static function defaultAsOf(int $year): string
    {
        return min(date('Y-m-d'), sprintf('%04d-12-31', $year));
    }

    /**
     * Période couverte par le bilan : du 1er janvier (ou de la date d'achat) à la date
     * d'arrêté (aujourd'hui par défaut). null si le bien n'était pas encore détenu.
     */
    public static function period(array $p, int $year, ?string $asOf = null): ?array
    {
        $from = sprintf('%04d-01-01', $year);
        $end  = sprintf('%04d-12-31', $year);
        if (!empty($p['purchase_date']) && $p['purchase_date'] > $from) $from = substr($p['purchase_date'], 0, 10);
        $to = min($end, max(sprintf('%04d-01-01', $year), $asOf ?: self::defaultAsOf($year)));
        $partial = $to < $end;
        if ($from > $to) return null;
        $days = (int) ((strtotime($to) - strtotime($from)) / 86400) + 1;
        $yearDays = (int) date('z', mktime(0, 0, 0, 12, 31, $year)) + 1;
        return ['from' => $from, 'to' => $to, 'to_date' => $partial, 'ratio' => min(1.0, $days / $yearDays)];
    }

    public static function compute(array $p, int $year, ?string $asOf = null): array
    {
        $pid = (int) $p['id'];
        // Période réellement couverte (jusqu'à la date d'arrêté) : tout est compté à cette date,
        // et les estimations annuelles sont proratisées dessus.
        $period = self::period($p, $year, $asOf) ?? ['from' => null, 'to' => null, 'to_date' => false, 'ratio' => 0.0];
        $ratio = $period['ratio'];
        $until = $period['to'] ?? sprintf('%04d-01-00', $year); // rien n'est compté si le bien n'était pas détenu

        // --- Encaissements de l'année (date de paiement) ---
        $loyers = 0.0; $provisionsCash = 0.0;
        $rows = Database::all(
            "SELECT rp.amount_rent, rp.amount_charges, rp.paid_date, rp.period_year, rp.period_month, rp.status
             FROM rent_payments rp JOIN leases l ON l.id = rp.lease_id WHERE l.property_id = ?",
            [$pid]
        );
        foreach ($rows as $r) {
            if ($r['status'] !== 'paid') continue;
            $periodStart = sprintf('%04d-%02d-01', $r['period_year'], $r['period_month']);
            $paid = $r['paid_date'] ? substr($r['paid_date'], 0, 10) : $periodStart;
            // Encaissé dans l'année, au plus tard à la date d'arrêté.
            if ((int) substr($paid, 0, 4) === $year && $paid <= $until) { $loyers += (float) $r['amount_rent']; $provisionsCash += (float) $r['amount_charges']; }
        }
        $encaissements = $loyers + $provisionsCash;

        // --- Charges et impôts : réel si saisi, sinon estimation de la fiche du bien ---
        $real = PropertyCost::totals($pid, $year, $until);
        $line = function (string $key, float $estimate) use ($real): array {
            return $real[$key] !== null
                ? ['amount' => $real[$key]['amount'], 'recoverable' => $real[$key]['recoverable'], 'estimated' => false]
                : ['amount' => $estimate, 'recoverable' => 0.0, 'estimated' => $estimate > 0];
        };
        // Emprunt : échéances réellement passées sur la période (à date pour l'année en cours).
        $untilMonth = $period['to'] ? (int) substr($period['to'], 5, 2) : 0;
        $loan = $untilMonth > 0 ? Lmnp::loanForYear($p, $year, $untilMonth) : ['payments' => 0.0, 'interest' => 0.0, 'months' => 0];

        $charges = [
            'copro'         => ['label' => 'Charges de copropriété (appels + régularisation)'] + $line('copro', round((float) $p['charges_year'] * $ratio, 2)),
            'taxe_fonciere' => ['label' => 'Taxe foncière'] + $line('taxe_fonciere', round((float) $p['property_tax'] * $ratio, 2)),
            'assurance'     => ['label' => 'Assurance propriétaire (PNO)'] + $line('assurance', round((float) $p['insurance_year'] * $ratio, 2)),
            'autre'         => ['label' => 'Autres charges'] + $line('autre', 0.0),
            'gestion'       => ['label' => 'Frais de gestion (' . rtrim(rtrim(number_format((float) $p['mgmt_fees_pct'], 2, ',', ''), '0'), ',') . ' % des loyers)',
                                'amount' => $loyers * (float) $p['mgmt_fees_pct'] / 100, 'recoverable' => 0.0, 'estimated' => false],
            'interets'      => ['label' => 'Intérêts et assurance d\'emprunt (' . $loan['months'] . ' échéance' . ($loan['months'] > 1 ? 's' : '') . ')',
                                'amount' => $loan['interest'] + ($loan['insurance'] ?? 0.0), 'recoverable' => 0.0, 'estimated' => false],
            'depenses'      => ['label' => 'Autres dépenses déductibles (fiche du bien)',
                                'amount' => self::expenses($pid, Expense::DEDUCTIBLE, $year, $until), 'recoverable' => 0.0, 'estimated' => false],
        ];
        $chargesTotal = array_sum(array_column($charges, 'amount'));

        // --- Investissements de l'année (travaux, mobilier, équipement) ---
        $investissements = self::expenses($pid, array_merge(Expense::AMORT_WORKS, Expense::AMORT_FURNITURE), $year, $until);

        // --- Emprunt : mensualités versées (capital + intérêts + assurance) ---
        $mensualites = $loan['payments'];
        $capital = max(0.0, $mensualites - $loan['interest'] - ($loan['insurance'] ?? 0.0));

        $resultat = $encaissements - $chargesTotal;                       // hors amortissements et hors capital remboursé
        $cashflow = $encaissements - $chargesTotal - $capital - $investissements;

        // --- Régularisation des charges locatives ---
        $recuperable = $charges['copro']['recoverable'] + $charges['taxe_fonciere']['recoverable'] + $charges['autre']['recoverable'];
        $copro = $real['copro'];
        $warnings = [];
        foreach ($charges as $k => $c) {
            if ($c['estimated']) $warnings[] = $c['label'] . ' : montant réel non saisi, estimation de la fiche du bien utilisée'
                . ($ratio < 0.999 ? ' (proratisée : ' . round($ratio * 100) . ' % de l\'année)' : '') . '.';
        }
        if ($copro !== null && $copro['amount'] > 0 && $copro['recoverable'] == 0.0) {
            $warnings[] = 'Charges de copropriété : part récupérable non renseignée (régularisation incomplète).';
        }
        if ($real['taxe_fonciere'] !== null && $real['taxe_fonciere']['recoverable'] == 0.0) {
            $warnings[] = 'Taxe foncière : TEOM (part récupérable) non renseignée.';
        }

        return [
            'year'            => $year,
            'period'          => $period,
            'loan_months'     => $loan['months'],
            'loyers'          => $loyers,
            'provisions_cash' => $provisionsCash,
            'encaissements'   => $encaissements,
            'charges'         => $charges,
            'charges_total'   => $chargesTotal,
            'investissements' => $investissements,
            'mensualites'     => $mensualites,
            'capital'         => $capital,
            'resultat'        => $resultat,
            'cashflow'        => $cashflow,
            'regul'           => self::regularisation($p, $year),
            'warnings'        => $warnings,
        ];
    }

    /**
     * Régularisation annuelle des charges récupérables, par locataire, sur l'EXERCICE complet
     * (indépendamment de la date d'arrêté) : chaque locataire ne supporte les charges que pour
     * ses jours d'occupation (art. 23 de la loi du 6 juillet 1989).
     * Part locataire = charges récupérables de l'année × jours occupés / jours de détention.
     */
    public static function regularisation(array $p, int $year): array
    {
        $pid = (int) $p['id'];
        $yStart = sprintf('%04d-01-01', $year);
        $yEnd   = sprintf('%04d-12-31', $year);
        $ownFrom = (!empty($p['purchase_date']) && $p['purchase_date'] > $yStart) ? substr($p['purchase_date'], 0, 10) : $yStart;
        $days = fn(string $a, string $b): int => $a > $b ? 0 : (int) round((strtotime($b) - strtotime($a)) / 86400) + 1;
        $ownDays = $days($ownFrom, $yEnd);

        $real = PropertyCost::totals($pid, $year); // toutes les charges de l'exercice
        // Les appels de charges ne sont que des provisions : le montant récupérable réel n'est connu
        // qu'avec le décompte annuel du syndic (ligne « Régularisation annuelle (syndic) »).
        $kinds = array_column(PropertyCost::forProperty($pid, $year), 'kind');
        $awaitingSyndic = in_array('copro_appel', $kinds, true) && !in_array('copro_regul', $kinds, true);
        $recuperable = 0.0;
        foreach (['copro', 'taxe_fonciere', 'autre'] as $k) $recuperable += (float) ($real[$k]['recoverable'] ?? 0);

        $leases = Database::all(
            "SELECT l.id, l.start_date, l.end_date, l.charge_type, t.first_name, t.last_name
             FROM leases l JOIN tenants t ON t.id = l.tenant_id
             WHERE l.property_id = ? AND l.start_date <= ? AND (l.end_date IS NULL OR l.end_date >= ?)
             ORDER BY l.start_date",
            [$pid, $yEnd, $ownFrom]
        );
        $rows = []; $solde = 0.0; $forfait = false; $occupied = 0;
        foreach ($leases as $i => $l) {
            $from = max($ownFrom, substr($l['start_date'], 0, 10));
            $to   = min($yEnd, $l['end_date'] ? substr($l['end_date'], 0, 10) : $yEnd);
            // Baux successifs : un bail sans date de fin (ou qui chevauche le suivant) s'arrête la veille du bail suivant.
            if (isset($leases[$i + 1])) {
                $next = substr($leases[$i + 1]['start_date'], 0, 10);
                if ($to >= $next) $to = date('Y-m-d', strtotime($next . ' -1 day'));
            }
            $d = $days($from, $to);
            if ($d <= 0) continue;
            $occupied += $d;
            if (($l['charge_type'] ?? 'provisions') === 'forfait') { $forfait = true; continue; }
            // Provisions appelées pour les mois de l'année (payées ou à venir).
            $prov = Database::one(
                "SELECT COALESCE(SUM(amount_charges),0) AS t, SUM(status = 'paid') AS paid, COUNT(*) AS n
                 FROM rent_payments WHERE lease_id = ? AND period_year = ?",
                [(int) $l['id'], $year]
            );
            $share = $ownDays > 0 ? round($recuperable * $d / $ownDays, 2) : 0.0;
            $sd = round($share - (float) $prov['t'], 2);
            $solde += $sd;
            $rows[] = [
                'tenant' => trim($l['first_name'] . ' ' . $l['last_name']), 'from' => $from, 'to' => $to, 'days' => $d,
                'share' => $share, 'provisions' => (float) $prov['t'], 'months' => (int) $prov['n'], 'paid' => (int) $prov['paid'],
                'solde' => $sd,
            ];
        }
        return [
            'applicable'  => (bool) $rows,
            'forfait'     => $forfait && !$rows,
            'own_from'    => $ownFrom,
            'own_days'    => $ownDays,
            'recuperable' => $recuperable,
            'rows'        => $rows,
            // Jours sans locataire : la part correspondante des charges récupérables reste au propriétaire.
            'vacant_days' => max(0, $ownDays - $occupied),
            'vacant_share' => $ownDays > 0 ? round($recuperable * max(0, $ownDays - $occupied) / $ownDays, 2) : 0.0,
            'solde'       => round($solde, 2),      // > 0 : à réclamer ; < 0 : à rembourser
            'provisoire'  => date('Y-m-d') < $yEnd,  // exercice pas encore terminé
            'attente_syndic' => $awaitingSyndic,     // pas de solde tant que le décompte du syndic n'est pas saisi
        ];
    }

    /** Dépenses de la fiche du bien datées entre le 1er janvier et la date d'arrêté. */
    private static function expenses(int $pid, array $categories, int $year, string $until): float
    {
        $in = implode(',', array_fill(0, count($categories), '?'));
        $row = Database::one(
            "SELECT COALESCE(SUM(amount),0) AS t FROM property_expenses
             WHERE property_id = ? AND category IN ($in) AND expense_date >= ? AND expense_date <= ?",
            array_merge([$pid], $categories, [sprintf('%04d-01-01', $year), $until])
        );
        return (float) ($row['t'] ?? 0);
    }

    /** Bilan de tous les biens + totaux. */
    public static function all(int $year, ?string $asOf = null): array
    {
        $rows = [];
        $tot = ['encaissements' => 0.0, 'charges_total' => 0.0, 'resultat' => 0.0, 'cashflow' => 0.0, 'regul' => 0.0, 'investissements' => 0.0, 'capital' => 0.0];
        foreach (Property::all() as $p) {
            $b = self::compute($p, $year, $asOf);
            $rows[] = ['p' => $p, 'b' => $b];
            foreach (['encaissements', 'charges_total', 'resultat', 'cashflow', 'investissements', 'capital'] as $k) $tot[$k] += $b[$k];
            if ($b['regul']['applicable'] && !$b['regul']['attente_syndic']) $tot['regul'] += $b['regul']['solde'];
        }
        return ['rows' => $rows, 'totals' => $tot];
    }
}
