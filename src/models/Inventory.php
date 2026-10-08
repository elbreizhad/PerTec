<?php
declare(strict_types=1);

/**
 * Inventaire du mobilier d'un bail meublé (annexe 1), coché dans l'application
 * puis imprimé dans le contrat. Éléments obligatoires : décret n° 2015-981.
 */
class Inventory
{
    public const MANDATORY = [
        'Literie comprenant couette ou couverture',
        'Dispositif d\'occultation des fenêtres dans les chambres (volets ou rideaux)',
        'Plaques de cuisson',
        'Four ou four à micro-ondes',
        'Réfrigérateur et congélateur (ou compartiment à -6 °C)',
        'Vaisselle nécessaire à la prise des repas',
        'Ustensiles de cuisine',
        'Table et sièges',
        'Étagères de rangement',
        'Luminaires',
        'Matériel d\'entretien ménager adapté au logement',
    ];

    /** Nombre de lignes libres « Autre ». */
    public const FREE_ROWS = 2;

    /**
     * Lignes de l'inventaire, avec ce qui a été coché :
     * [['key','label','free'(bool),'present'('oui'|'non'|null),'notes'], …]
     */
    public static function rows(array $lease): array
    {
        $saved = [];
        if (!empty($lease['id'])) {
            try {
                foreach (Database::all('SELECT * FROM lease_inventory WHERE lease_id = ?', [(int) $lease['id']]) as $r) {
                    $saved[$r['item_key']] = $r;
                }
            } catch (Throwable $e) {
                // Table pas encore créée (migration en attente) : inventaire vierge.
            }
        }
        $items = [];
        foreach (self::MANDATORY as $i => $label) $items[] = ['key' => 'o' . ($i + 1), 'label' => $label, 'free' => false];
        $extra = array_filter(array_map('trim', explode("\n", (string) ($lease['furniture_extra'] ?? ''))));
        foreach ($extra as $label) $items[] = ['key' => 'x' . substr(md5($label), 0, 12), 'label' => $label, 'free' => false];
        for ($i = 1; $i <= self::FREE_ROWS; $i++) $items[] = ['key' => 'autre' . $i, 'label' => '', 'free' => true];

        foreach ($items as &$it) {
            $s = $saved[$it['key']] ?? null;
            if ($it['free']) $it['label'] = (string) ($s['label'] ?? '');
            $it['present'] = in_array($s['present'] ?? null, ['oui', 'non'], true) ? $s['present'] : null;
            $it['notes'] = (string) ($s['notes'] ?? '');
        }
        return $items;
    }

    /** Enregistre l'inventaire envoyé par le formulaire (tableaux indexés par clé de ligne). */
    public static function save(array $lease, array $present, array $notes, array $labels): void
    {
        $leaseId = (int) $lease['id'];
        Database::query('DELETE FROM lease_inventory WHERE lease_id = ?', [$leaseId]);
        foreach (self::rows(['id' => 0] + $lease) as $it) {
            $k = $it['key'];
            $p = in_array($present[$k] ?? '', ['oui', 'non'], true) ? $present[$k] : null;
            $n = trim(mb_substr((string) ($notes[$k] ?? ''), 0, 255));
            $label = $it['free'] ? trim(mb_substr((string) ($labels[$k] ?? ''), 0, 255)) : $it['label'];
            if ($p === null && $n === '' && (!$it['free'] || $label === '')) continue;
            Database::insert('lease_inventory', [
                'lease_id' => $leaseId, 'item_key' => $k, 'label' => $label ?: null,
                'present' => $p, 'notes' => $n ?: null,
            ]);
        }
    }

    /** [nombre de lignes renseignées, nombre de lignes à renseigner] (hors lignes libres vides). */
    public static function progress(array $rows): array
    {
        $todo = array_filter($rows, fn($r) => !$r['free'] || $r['label'] !== '');
        return [count(array_filter($todo, fn($r) => $r['present'] !== null)), count($todo)];
    }
}
