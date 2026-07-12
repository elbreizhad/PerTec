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
}
