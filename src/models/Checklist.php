<?php
declare(strict_types=1);

/** Checklist de conformité d'un bail (côté bailleur + pièces du locataire). */
class Checklist
{
    public const GROUPS = [
        'bailleur'  => 'Conformité côté bailleur',
        'locataire' => 'Pièces fournies par le locataire',
        'suivi'     => 'Suivi',
    ];

    /** Définition des items. 'meuble_only' => n'apparaît que pour un bail meublé. */
    public const ITEMS = [
        ['key' => 'bail_signe',           'group' => 'bailleur',  'label' => 'Bail signé par les deux parties'],
        ['key' => 'etat_lieux_entree',    'group' => 'bailleur',  'label' => "État des lieux d'entrée réalisé et signé"],
        ['key' => 'depot_encaisse',       'group' => 'bailleur',  'label' => 'Dépôt de garantie encaissé'],
        ['key' => 'diagnostics_remis',    'group' => 'bailleur',  'label' => 'Dossier de diagnostics (DPE, plomb, amiante…) remis'],
        ['key' => 'notice_information',   'group' => 'bailleur',  'label' => "Notice d'information jointe au bail"],
        ['key' => 'inventaire_mobilier',  'group' => 'bailleur',  'label' => 'Inventaire du mobilier réalisé et signé', 'meuble_only' => true],
        ['key' => 'assurance_pno',        'group' => 'bailleur',  'label' => 'Assurance PNO (propriétaire non occupant) souscrite'],

        ['key' => 'piece_identite',       'group' => 'locataire', 'label' => "Pièce d'identité"],
        ['key' => 'justif_domicile',      'group' => 'locataire', 'label' => 'Justificatif de domicile précédent'],
        ['key' => 'justif_revenus',       'group' => 'locataire', 'label' => 'Justificatifs de revenus (bulletins / avis d\'imposition)'],
        ['key' => 'attestation_assurance','group' => 'locataire', 'label' => "Attestation d'assurance habitation"],
        ['key' => 'rib',                  'group' => 'locataire', 'label' => 'RIB / mandat de prélèvement'],
        ['key' => 'garant',               'group' => 'locataire', 'label' => 'Acte de cautionnement + pièces du garant (si applicable)'],

        ['key' => 'premier_loyer',        'group' => 'suivi',     'label' => 'Premier loyer encaissé'],
        ['key' => 'quittance_fournie',    'group' => 'suivi',     'label' => 'Quittances transmises au locataire'],
    ];

    /** Items applicables à un bail selon son type. */
    public static function applicableItems(string $leaseType): array
    {
        return array_values(array_filter(self::ITEMS, function ($item) use ($leaseType) {
            return empty($item['meuble_only']) || $leaseType === 'meuble';
        }));
    }

    /** Clés valides (toutes). */
    private static function validKeys(): array
    {
        return array_column(self::ITEMS, 'key');
    }

    /** Clés cochées pour un bail. */
    public static function checkedKeys(int $leaseId): array
    {
        $rows = Database::all('SELECT item_key FROM lease_checklist WHERE lease_id = ?', [$leaseId]);
        return array_column($rows, 'item_key');
    }

    /** Enregistre la liste des clés cochées (remplace l'existant). */
    public static function save(int $leaseId, array $keys): void
    {
        $valid = self::validKeys();
        $keys = array_values(array_unique(array_intersect($keys, $valid)));

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            Database::query('DELETE FROM lease_checklist WHERE lease_id = ?', [$leaseId]);
            foreach ($keys as $key) {
                Database::query(
                    'INSERT INTO lease_checklist (lease_id, item_key) VALUES (?, ?)',
                    [$leaseId, $key]
                );
            }
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /** Progression : [cochés, total] pour un bail. */
    public static function progress(int $leaseId, string $leaseType): array
    {
        $applicable = array_column(self::applicableItems($leaseType), 'key');
        $checked = array_intersect(self::checkedKeys($leaseId), $applicable);
        return [count($checked), count($applicable)];
    }
}
