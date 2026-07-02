<?php
declare(strict_types=1);

/** Dépenses détaillées d'un bien (travaux, achat, aménagement…). */
class Expense
{
    /** Catégorie => libellé + groupe fiscal LMNP. */
    public const CATEGORIES = [
        'travaux'     => 'Travaux',
        'achat'       => 'Achat / Équipement',
        'amenagement' => 'Aménagement',
        'mobilier'    => 'Mobilier',
        'autre'       => 'Autre (charge déductible)',
    ];

    /** Groupes d'amortissement LMNP. */
    public const AMORT_FURNITURE = ['achat', 'amenagement', 'mobilier'];
    public const AMORT_WORKS     = ['travaux'];
    public const DEDUCTIBLE      = ['autre'];

    public static function forProperty(int $propertyId): array
    {
        return Database::all(
            'SELECT * FROM property_expenses WHERE property_id = ?
             ORDER BY expense_date DESC, id DESC',
            [$propertyId]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM property_expenses WHERE id = ?', [$id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('property_expenses', $data);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM property_expenses WHERE id = ?', [$id]);
    }

    public static function fromRequest(int $propertyId): array
    {
        $cat = (string) post('category');
        if (!isset(self::CATEGORIES[$cat])) $cat = 'travaux';
        return [
            'property_id'  => $propertyId,
            'category'     => $cat,
            'label'        => trim((string) post('label')) ?: self::CATEGORIES[$cat],
            'amount'       => num(post('amount')),
            'expense_date' => post('expense_date') ?: null,
            'notes'        => post('notes') ?: null,
        ];
    }

    /** Total de toutes les dépenses d'un bien. */
    public static function totalForProperty(int $propertyId): float
    {
        $row = Database::one(
            'SELECT COALESCE(SUM(amount),0) AS t FROM property_expenses WHERE property_id = ?',
            [$propertyId]
        );
        return (float) ($row['t'] ?? 0);
    }

    /** Total des dépenses d'un bien pour un ensemble de catégories. */
    public static function totalByCategories(int $propertyId, array $categories, ?int $year = null): float
    {
        if (!$categories) return 0.0;
        $in = implode(',', array_fill(0, count($categories), '?'));
        $sql = "SELECT COALESCE(SUM(amount),0) AS t FROM property_expenses
                WHERE property_id = ? AND category IN ($in)";
        $params = array_merge([$propertyId], $categories);
        if ($year !== null) {
            // Plage de dates (portable MySQL/SQLite, contrairement à YEAR()).
            $sql .= ' AND expense_date >= ? AND expense_date < ?';
            $params[] = sprintf('%04d-01-01', $year);
            $params[] = sprintf('%04d-01-01', $year + 1);
        }
        $row = Database::one($sql, $params);
        return (float) ($row['t'] ?? 0);
    }
}
