<?php
declare(strict_types=1);

class Lease
{
    /** Baux avec libellé bien + nom locataire. */
    public static function all(): array
    {
        return Database::all(
            "SELECT l.*, p.label AS property_label, p.address, p.postal_code, p.city,
                    t.first_name, t.last_name
             FROM leases l
             JOIN properties p ON p.id = l.property_id
             JOIN tenants t    ON t.id = l.tenant_id
             ORDER BY l.status ASC, l.start_date DESC"
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one(
            "SELECT l.*, p.label AS property_label, p.address, p.postal_code, p.city,
                    p.surface_m2, p.rooms, p.type AS property_type,
                    t.first_name, t.last_name, t.email, t.phone
             FROM leases l
             JOIN properties p ON p.id = l.property_id
             JOIN tenants t    ON t.id = l.tenant_id
             WHERE l.id = ?",
            [$id]
        );
    }

    public static function forProperty(int $propertyId): array
    {
        return Database::all(
            "SELECT l.*, t.first_name, t.last_name
             FROM leases l JOIN tenants t ON t.id = l.tenant_id
             WHERE l.property_id = ? ORDER BY l.start_date DESC",
            [$propertyId]
        );
    }

    public static function fromRequest(): array
    {
        return [
            'property_id'    => (int) post('property_id'),
            'tenant_id'      => (int) post('tenant_id'),
            'lease_type'     => post('lease_type') === 'meuble' ? 'meuble' : 'vide',
            'start_date'     => post('start_date') ?: date('Y-m-d'),
            'end_date'       => post('end_date') ?: null,
            'rent_amount'    => num(post('rent_amount')),
            'charges_amount' => num(post('charges_amount')),
            'deposit_amount' => num(post('deposit_amount')),
            'payment_day'    => max(1, min(28, (int) post('payment_day', 1))),
            'status'         => post('status') === 'terminated' ? 'terminated' : 'active',
            'notes'          => post('notes') ?: null,
            'furniture_extra' => post('furniture_extra') ?: null,
            'charge_type'     => post('charge_type') === 'forfait' ? 'forfait' : 'provisions',
            'signature_date'  => post('signature_date') ?: null,
            'tenant_current_address' => post('tenant_current_address') ?: null,
            'guarantor_name'        => post('guarantor_name') ?: null,
            'guarantor_address'     => post('guarantor_address') ?: null,
            'guarantor_birth_date'  => post('guarantor_birth_date') ?: null,
            'guarantor_birth_place' => post('guarantor_birth_place') ?: null,
            'guarantor_email'       => post('guarantor_email') ?: null,
            'guarantor_phone'       => post('guarantor_phone') ?: null,
            'guarantor_max_amount'  => post('guarantor_max_amount') !== null && post('guarantor_max_amount') !== ''
                ? num(post('guarantor_max_amount')) : null,
            'guarantor_duration'    => post('guarantor_duration') === 'determinee' ? 'determinee' : 'indeterminee',
            'guarantor2_name'        => post('guarantor2_name') ?: null,
            'guarantor2_address'     => post('guarantor2_address') ?: null,
            'guarantor2_birth_date'  => post('guarantor2_birth_date') ?: null,
            'guarantor2_birth_place' => post('guarantor2_birth_place') ?: null,
            'guarantor2_email'       => post('guarantor2_email') ?: null,
            'guarantor2_phone'       => post('guarantor2_phone') ?: null,
            'guarantor2_max_amount'  => post('guarantor2_max_amount') !== null && post('guarantor2_max_amount') !== ''
                ? num(post('guarantor2_max_amount')) : null,
            'guarantor2_duration'    => post('guarantor2_duration') === 'determinee' ? 'determinee' : 'indeterminee',
        ];
    }

    public static function create(array $data): int
    {
        return Database::insert('leases', $data);
    }

    public static function update(int $id, array $data): void
    {
        Database::update('leases', $data, 'id = :id', ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM leases WHERE id = ?', [$id]);
    }

    /**
     * Liste normalisée des garants déclarés sur le bail (0, 1 ou 2).
     * Chaque garant : ['name','address','birth_date','birth_place','email',
     * 'phone','max_amount','duration']. Seuls les garants nommés sont retournés.
     * Réf. art. 22-1 de la loi n° 89-462 du 6 juillet 1989 (cautionnement).
     */
    public static function guarantors(array $lease): array
    {
        $out = [];
        foreach (['', '2'] as $suffix) {
            $name = trim((string) ($lease["guarantor{$suffix}_name"] ?? ''));
            if ($name === '') continue;
            $out[] = [
                'name'        => $name,
                'address'     => $lease["guarantor{$suffix}_address"]     ?? null,
                'birth_date'  => $lease["guarantor{$suffix}_birth_date"]  ?? null,
                'birth_place' => $lease["guarantor{$suffix}_birth_place"] ?? null,
                'email'       => $lease["guarantor{$suffix}_email"]       ?? null,
                'phone'       => $lease["guarantor{$suffix}_phone"]       ?? null,
                'max_amount'  => $lease["guarantor{$suffix}_max_amount"]  ?? null,
                'duration'    => $lease["guarantor{$suffix}_duration"]    ?? 'indeterminee',
            ];
        }
        return $out;
    }

    /**
     * Contrôle avant génération du bail meublé : renvoie les problèmes
     * détectés, séparés en « blocking » (empêchent une génération valable) et
     * « warnings » (à vérifier). Sert à la fois de garde-fou à la génération et
     * de checklist affichée sur la fiche du bail.
     *
     * Fondements : loi n° 89-462 du 6 juillet 1989 (art. 3 mentions obligatoires,
     * art. 8-1 charges, art. 17-1 révision IRL, art. 22 dépôt de garantie,
     * art. 25-3 et s. bail meublé) ; loi ALUR n° 2014-366 ; décret n° 2015-981.
     *
     * @return array{blocking:string[],warnings:string[]}
     */
    public static function contractIssues(array $lease, array $settings): array
    {
        $blocking = [];
        $warnings = [];

        // — Bailleur (art. 3 : identité et domicile du bailleur) —
        if (trim((string)($settings['landlord_name'] ?? '')) === '')
            $blocking[] = 'Coordonnées du bailleur : le nom est manquant (Paramètres).';
        if (trim((string)($settings['landlord_address'] ?? '')) === '')
            $blocking[] = 'Coordonnées du bailleur : l’adresse est manquante (Paramètres).';
        $ville = trim((string)($settings['signature_city'] ?? ($settings['landlord_city'] ?? '')));
        if ($ville === '')
            $blocking[] = 'Ville de signature manquante (Paramètres) — nécessaire pour la mention « Fait à … ».';

        // — Locataire (art. 3 : identité du locataire) —
        if (trim((string)($lease['first_name'] ?? '') . ($lease['last_name'] ?? '')) === '')
            $blocking[] = 'Identité du locataire manquante.';

        // — Logement (art. 3 + loi ALUR : désignation et surface habitable) —
        $adresse = trim((string)($lease['address'] ?? '') . (string)($lease['city'] ?? ''));
        if ($adresse === '' && trim((string)($lease['property_label'] ?? '')) === '')
            $blocking[] = 'Adresse du logement manquante sur le bien.';
        if (empty($lease['surface_m2']))
            $blocking[] = 'Surface habitable manquante (obligatoire — loi ALUR).';
        if (empty($lease['rooms']))
            $warnings[] = 'Nombre de pièces non renseigné sur le bien.';

        // — Conditions financières (art. 3, 17-1) —
        if ((float)($lease['rent_amount'] ?? 0) <= 0)
            $blocking[] = 'Loyer hors charges manquant ou nul.';
        if (empty($lease['start_date']))
            $blocking[] = 'Date de début du bail manquante.';

        // — Dépôt de garantie (art. 25-6 meublé : max 2 mois de loyer HC) —
        $rent = (float)($lease['rent_amount'] ?? 0);
        $deposit = (float)($lease['deposit_amount'] ?? 0);
        if ($rent > 0 && $deposit > 2 * $rent + 0.001)
            $warnings[] = sprintf('Dépôt de garantie (%s) supérieur au maximum légal de 2 mois de loyer HC (%s).',
                euros($deposit), euros(2 * $rent));

        // — Garant : cohérence bail / acte de cautionnement (art. 22-1) —
        foreach (self::guarantors($lease) as $i => $g) {
            if (empty($g['max_amount']))
                $warnings[] = sprintf('Garant « %s » : montant maximal garanti non renseigné (recommandé).', $g['name']);
        }

        // — Annexes remises séparément (rappel, non bloquant) —
        $warnings[] = 'Pensez à joindre au locataire : DPE, état des risques et pollutions (ERP) et notice d’information (annexes séparées).';

        return ['blocking' => $blocking, 'warnings' => $warnings];
    }
}
