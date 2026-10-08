<?php
declare(strict_types=1);

/**
 * Charges et impôts RÉELS d'un bien, année par année : appels de charges de
 * copropriété, régularisation annuelle du syndic, taxe foncière (dont TEOM),
 * assurance PNO, autres charges. Chaque ligne a une part « récupérable »
 * auprès du locataire (décret n° 87-713 du 26 août 1987), qui sert à la
 * régularisation annuelle des charges.
 */
class PropertyCost
{
    /** type => [libellé, aide pour la part récupérable] */
    public const KINDS = [
        'copro_appel'   => ['Appel de charges de copropriété', 'Part récupérable : charges locatives (entretien, eau, ascenseur, ménage…), si connue.'],
        'copro_regul'   => ['Régularisation annuelle (syndic)', 'Montant du décompte annuel du syndic : positif (complément) ou négatif (trop-perçu).'],
        'taxe_fonciere' => ['Taxe foncière', 'Part récupérable : la TEOM (taxe d\'enlèvement des ordures ménagères) figurant sur l\'avis.'],
        'assurance'     => ['Assurance propriétaire (PNO)', 'Non récupérable.'],
        'autre'         => ['Autre charge', 'Indiquez la part récupérable s\'il y en a une.'],
    ];

    public static function label(array $c): string
    {
        return !empty($c['label']) ? $c['label'] : (self::KINDS[$c['kind']][0] ?? 'Charge');
    }

    public static function forProperty(int $propertyId, ?int $year = null): array
    {
        try {
            $sql = 'SELECT * FROM property_costs WHERE property_id = ?';
            $params = [$propertyId];
            if ($year !== null) { $sql .= ' AND year = ?'; $params[] = $year; }
            return Database::all($sql . ' ORDER BY year DESC, cost_date, id', $params);
        } catch (Throwable $e) {
            return []; // migration en attente
        }
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM property_costs WHERE id = ?', [$id]);
    }

    public static function fromRequest(int $propertyId): array
    {
        $kind = (string) post('kind');
        if (!isset(self::KINDS[$kind])) $kind = 'autre';
        $amount = num(post('amount'));
        $recoverable = num(post('recoverable'));
        // La part récupérable ne peut pas dépasser le montant (même signe en cas de régularisation négative).
        if ($amount >= 0) $recoverable = max(0.0, min($recoverable, $amount));
        else $recoverable = min(0.0, max($recoverable, $amount));
        $date = post('cost_date') ?: null;
        $year = (int) post('year') ?: ($date ? (int) substr($date, 0, 4) : (int) date('Y'));
        if ($kind === 'assurance') $recoverable = 0.0;
        return [
            'property_id' => $propertyId,
            'kind'        => $kind,
            'label'       => trim((string) post('label')) ?: null,
            'year'        => $year,
            'cost_date'   => $date,
            'amount'      => $amount,
            'recoverable' => $recoverable,
            'notes'       => trim((string) post('notes')) ?: null,
        ];
    }

    public static function create(array $data): int
    {
        return Database::insert('property_costs', $data);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM property_costs WHERE id = ?', [$id]);
    }

    /**
     * Totaux réels d'une année par type : ['copro' => [amount, recoverable], 'taxe_fonciere' => …, …]
     * (« copro » regroupe appels + régularisation). null pour un type sans saisie.
     */
    public static function totals(int $propertyId, int $year): array
    {
        $out = ['copro' => null, 'taxe_fonciere' => null, 'assurance' => null, 'autre' => null];
        foreach (self::forProperty($propertyId, $year) as $c) {
            $k = str_starts_with($c['kind'], 'copro') ? 'copro' : $c['kind'];
            if (!array_key_exists($k, $out)) $k = 'autre';
            $out[$k] ??= ['amount' => 0.0, 'recoverable' => 0.0];
            $out[$k]['amount'] += (float) $c['amount'];
            $out[$k]['recoverable'] += (float) $c['recoverable'];
        }
        return $out;
    }
}
