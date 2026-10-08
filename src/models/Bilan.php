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
    /**
     * Période couverte par le bilan : de l'achat (s'il a lieu dans l'année) au 31/12,
     * ou jusqu'à aujourd'hui pour l'année en cours. null si le bien n'était pas détenu.
     */
    public static function period(array $p, int $year): ?array
    {
        $from = sprintf('%04d-01-01', $year);
        $to   = sprintf('%04d-12-31', $year);
        if (!empty($p['purchase_date']) && $p['purchase_date'] > $from) $from = substr($p['purchase_date'], 0, 10);
        $today = date('Y-m-d');
        $partial = false;
        if ($today < $to) { $to = $today; $partial = true; }
        if ($from > $to) return null;
        $days = (int) ((strtotime($to) - strtotime($from)) / 86400) + 1;
        $yearDays = (int) date('z', mktime(0, 0, 0, 12, 31, $year)) + 1;
        return ['from' => $from, 'to' => $to, 'to_date' => $partial, 'ratio' => min(1.0, $days / $yearDays)];
    }

    public static function compute(array $p, int $year): array
    {
        $pid = (int) $p['id'];
        // Période réellement couverte : les estimations annuelles sont proratisées dessus.
        $period = self::period($p, $year) ?? ['from' => null, 'to' => null, 'to_date' => false, 'ratio' => 0.0];
        $ratio = $period['ratio'];

        // --- Encaissements de l'année (date de paiement) ---
        $loyers = 0.0; $provisionsCash = 0.0;
        // --- Provisions appelées pour les mois de l'année (base de la régularisation) ---
        $provisionsYear = 0.0;
        $forfait = false;
        $rented = false; // au moins un mois loué dans l'année
        $rows = Database::all(
            "SELECT rp.amount_rent, rp.amount_charges, rp.paid_date, rp.period_year, rp.status, l.charge_type
             FROM rent_payments rp JOIN leases l ON l.id = rp.lease_id WHERE l.property_id = ?",
            [$pid]
        );
        foreach ($rows as $r) {
            if ($r['status'] !== 'paid') continue;
            $y = $r['paid_date'] ? (int) substr($r['paid_date'], 0, 4) : (int) $r['period_year'];
            if ($y === $year) { $loyers += (float) $r['amount_rent']; $provisionsCash += (float) $r['amount_charges']; }
            if ((int) $r['period_year'] === $year) {
                $rented = true;
                if (($r['charge_type'] ?? 'provisions') === 'forfait') $forfait = true;
                else $provisionsYear += (float) $r['amount_charges'];
            }
        }
        $encaissements = $loyers + $provisionsCash;

        // --- Charges et impôts : réel si saisi, sinon estimation de la fiche du bien ---
        $real = PropertyCost::totals($pid, $year);
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
            'interets'      => ['label' => 'Intérêts d\'emprunt (' . $loan['months'] . ' échéance' . ($loan['months'] > 1 ? 's' : '') . ')',
                                'amount' => $loan['interest'], 'recoverable' => 0.0, 'estimated' => false],
            'depenses'      => ['label' => 'Autres dépenses déductibles (fiche du bien)',
                                'amount' => Expense::totalByCategories($pid, Expense::DEDUCTIBLE, $year), 'recoverable' => 0.0, 'estimated' => false],
        ];
        $chargesTotal = array_sum(array_column($charges, 'amount'));

        // --- Investissements de l'année (travaux, mobilier, équipement) ---
        $investissements = Expense::totalByCategories($pid, array_merge(Expense::AMORT_WORKS, Expense::AMORT_FURNITURE), $year);

        // --- Emprunt : mensualités versées (capital + intérêts + assurance) ---
        $mensualites = $loan['payments'];
        $capital = max(0.0, $mensualites - $loan['interest']);

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
            'regul' => [
                'applicable'   => $rented && !$forfait,
                'forfait'      => $forfait,
                'provisions'   => $provisionsYear,
                'recuperable'  => $recuperable,
                'solde'        => $recuperable - $provisionsYear, // > 0 : à réclamer ; < 0 : à rembourser
            ],
            'warnings'        => $warnings,
        ];
    }

    /** Bilan de tous les biens + totaux. */
    public static function all(int $year): array
    {
        $rows = [];
        $tot = ['encaissements' => 0.0, 'charges_total' => 0.0, 'resultat' => 0.0, 'cashflow' => 0.0, 'regul' => 0.0, 'investissements' => 0.0, 'capital' => 0.0];
        foreach (Property::all() as $p) {
            $b = self::compute($p, $year);
            $rows[] = ['p' => $p, 'b' => $b];
            foreach (['encaissements', 'charges_total', 'resultat', 'cashflow', 'investissements', 'capital'] as $k) $tot[$k] += $b[$k];
            if ($b['regul']['applicable']) $tot['regul'] += $b['regul']['solde'];
        }
        return ['rows' => $rows, 'totals' => $tot];
    }
}
