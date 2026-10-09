<?php
declare(strict_types=1);

/**
 * Projection pluriannuelle de la rentabilité d'un bien (avant impôt sur le revenu) :
 * loyers indexés, vacance locative, charges qui augmentent, tableau d'amortissement
 * du prêt, cash-flow, capital restant dû et patrimoine net (valeur estimée − dette).
 *
 * Bases : loyer hors charges du bail en cours ; charges annuelles de la fiche du bien
 * (taxe foncière, PNO, charges de copropriété NON récupérables, gestion, comptable) —
 * les charges récupérables sont refacturées au locataire et n'entrent pas dans le calcul.
 *
 * ⚠️ Simulation indicative : ne remplace pas un conseil financier ou fiscal.
 */
class Projection
{
    public const DEFAULTS = [
        'annees'   => 20,  // horizon
        'loyer'    => 1.5, // indexation annuelle des loyers (IRL), %
        'charges'  => 2.0, // hausse annuelle des charges, %
        'vacance'  => 0.5, // mois sans locataire par an
        'valeur'   => 1.0, // évolution annuelle de la valeur du bien, %
    ];

    /** Hypothèses lues dans la requête (bornées), sinon valeurs par défaut. */
    public static function params(array $src): array
    {
        $p = self::DEFAULTS;
        $bounds = ['annees' => [1, 40], 'loyer' => [-5, 10], 'charges' => [-5, 15], 'vacance' => [0, 12], 'valeur' => [-10, 10]];
        foreach ($bounds as $k => [$min, $max]) {
            if (isset($src[$k]) && $src[$k] !== '') $p[$k] = max($min, min($max, num($src[$k])));
        }
        $p['annees'] = (int) $p['annees'];
        return $p;
    }

    public static function compute(array $prop, array $h): array
    {
        $startYear = (int) date('Y') + 1; // première année complète
        $rentMonthly = Finance::currentMonthlyRent((int) $prop['id']);
        // Révision IRL à chaque date anniversaire du bail en cours (le loyer actuel inclut déjà les révisions passées).
        $lease = Database::one("SELECT start_date FROM leases WHERE property_id = ? AND status = 'active' ORDER BY start_date DESC LIMIT 1", [(int) $prop['id']]);
        $anniv = $lease ? substr($lease['start_date'], 5, 5) : '01-01'; // MM-JJ
        $firstRevisionYear = $lease ? (int) substr($lease['start_date'], 0, 4) + 1 : (int) date('Y') + 1; // 1er anniversaire
        $today = date('Y-m-d');
        $cost = Finance::totalCost($prop);
        $baseCharges = (float) $prop['property_tax'] + (float) $prop['insurance_year'] + (float) $prop['charges_year']
            + (float) ($prop['accountant_fees'] ?? 0);
        $mgmtPct = (float) $prop['mgmt_fees_pct'] / 100;
        $value0 = (float) $prop['purchase_price'];
        $purchaseYear = !empty($prop['purchase_date']) ? (int) substr($prop['purchase_date'], 0, 4) : (int) date('Y');

        $rows = []; $cumul = 0.0; $loanEnd = null;
        for ($i = 0; $i < $h['annees']; $i++) {
            $y = $startYear + $i;
            // Loyer mois par mois : nombre de révisions intervenues depuis aujourd'hui au début du mois.
            $loyersBruts = 0.0;
            for ($m = 1; $m <= 12; $m++) {
                $monthStart = sprintf('%04d-%02d-01', $y, $m);
                $revisions = 0;
                for ($ay = max((int) date('Y'), $firstRevisionYear); $ay <= $y; $ay++) {
                    $d = $ay . '-' . $anniv;
                    if ($d > $today && $d <= $monthStart) $revisions++;
                }
                $loyersBruts += $rentMonthly * pow(1 + $h['loyer'] / 100, $revisions);
            }
            $loyers = $loyersBruts * max(0, 12 - $h['vacance']) / 12;
            $charges = $baseCharges * pow(1 + $h['charges'] / 100, $i) + $loyers * $mgmtPct;
            $loan = Lmnp::loanForYear($prop, $y);
            $interets = $loan['interest'] + $loan['insurance']; // intérêts + assurance emprunteur
            $capital = max(0.0, $loan['payments'] - $interets);
            $resultat = $loyers - $charges - $interets;
            $cashflow = $loyers - $charges - $loan['payments'];
            $cumul += $cashflow;
            $valeur = $value0 * pow(1 + $h['valeur'] / 100, max(0, $y - $purchaseYear)); // valeur estimée fin d'année
            if ($loanEnd === null && (float) $prop['loan_amount'] > 0 && $loan['balance'] <= 0.01 && $loan['months'] > 0) $loanEnd = $y;
            $rows[] = [
                'year' => $y, 'loyers' => $loyers, 'charges' => $charges, 'interets' => $interets, 'capital' => $capital,
                'mensualites' => $loan['payments'], 'resultat' => $resultat, 'cashflow' => $cashflow, 'cumul' => $cumul,
                'crd' => $loan['balance'], 'valeur' => $valeur, 'patrimoine' => $valeur - $loan['balance'],
                'renta_nette' => $cost > 0 ? ($loyers - $charges) / $cost * 100 : 0.0,
            ];
        }
        $first = $rows[0] ?? null;
        return [
            'hyp'        => $h,
            'rent'       => $rentMonthly,
            'cost'       => $cost,
            'rows'       => $rows,
            'brute'      => $cost > 0 ? $rentMonthly * 12 / $cost * 100 : 0.0,
            'nette'      => $first['renta_nette'] ?? 0.0,
            'cf_mensuel' => $first ? $first['cashflow'] / 12 : 0.0,
            'cf_moyen'   => $rows ? $cumul / count($rows) / 12 : 0.0,
            'loan_end'   => $loanEnd,
            'cumul'      => $cumul,
            'patrimoine' => $rows ? end($rows)['patrimoine'] : 0.0,
            // Gain total à l'horizon : cash-flows cumulés + patrimoine net − apport (coût total − emprunt).
            'apport'     => max(0.0, $cost - (float) $prop['loan_amount']),
        ];
    }

    /** Projection de tous les biens, année par année (sommes). */
    public static function all(array $h): array
    {
        $per = []; $tot = [];
        foreach (Property::all() as $p) {
            $r = self::compute($p, $h);
            $per[] = ['p' => $p, 'r' => $r];
            foreach ($r['rows'] as $i => $row) {
                foreach ($row as $k => $v) {
                    if ($k === 'year') { $tot[$i]['year'] = $v; continue; }
                    if ($k === 'renta_nette') continue;
                    $tot[$i][$k] = ($tot[$i][$k] ?? 0.0) + $v;
                }
            }
        }
        return ['per' => $per, 'rows' => $tot];
    }
}
