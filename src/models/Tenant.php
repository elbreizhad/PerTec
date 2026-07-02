<?php
declare(strict_types=1);

class Tenant
{
    public static function all(): array
    {
        return Database::all('SELECT * FROM tenants ORDER BY last_name, first_name');
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM tenants WHERE id = ?', [$id]);
    }

    public static function fromRequest(): array
    {
        return [
            'first_name' => trim((string) post('first_name')) ?: 'Prénom',
            'last_name'  => trim((string) post('last_name')) ?: 'Nom',
            'email'      => (post('email') ?: null),
            'phone'      => (post('phone') ?: null),
            'notes'      => (post('notes') ?: null),
        ];
    }

    public static function fullName(array $t): string
    {
        return trim($t['first_name'] . ' ' . $t['last_name']);
    }

    public static function create(array $data): int
    {
        return Database::insert('tenants', $data);
    }

    public static function update(int $id, array $data): void
    {
        Database::update('tenants', $data, 'id = :id', ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM tenants WHERE id = ?', [$id]);
    }
}
