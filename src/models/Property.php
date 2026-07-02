<?php
declare(strict_types=1);

class Property
{
    public const FIELDS = [
        'label','type','address','postal_code','city','surface_m2','rooms',
        'purchase_date','purchase_price','notary_fees','agency_fees','works_cost',
        'other_costs','loan_amount','loan_rate','loan_duration_months','loan_monthly',
        'property_tax','insurance_year','charges_year','mgmt_fees_pct',
        'land_share_pct','amort_years_building','amort_years_furniture',
        'amort_years_works','accountant_fees','tax_regime','notes',
    ];

    public const TYPES = ['appartement','maison','studio','immeuble','parking','local commercial','terrain'];

    public static function all(): array
    {
        return Database::all('SELECT * FROM properties ORDER BY label');
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM properties WHERE id = ?', [$id]);
    }

    public static function fromRequest(): array
    {
        $numFields = ['surface_m2','rooms','purchase_price','notary_fees','agency_fees',
            'works_cost','other_costs','loan_amount','loan_rate','loan_duration_months',
            'loan_monthly','property_tax','insurance_year','charges_year','mgmt_fees_pct',
            'land_share_pct','amort_years_building','amort_years_furniture',
            'amort_years_works','accountant_fees'];
        $data = [];
        foreach (self::FIELDS as $f) {
            $v = post($f);
            if (in_array($f, $numFields, true)) {
                $data[$f] = num($v);
            } elseif ($f === 'purchase_date') {
                $data[$f] = $v ?: null;
            } elseif ($f === 'tax_regime') {
                $data[$f] = $v === 'micro' ? 'micro' : 'reel';
            } else {
                $data[$f] = $v !== '' ? $v : ($f === 'label' ? 'Sans nom' : null);
            }
        }
        if ($data['type'] === null) $data['type'] = 'appartement';
        return $data;
    }

    public static function create(array $data): int
    {
        return Database::insert('properties', $data);
    }

    public static function update(int $id, array $data): void
    {
        Database::update('properties', $data, 'id = :id', ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM properties WHERE id = ?', [$id]);
    }
}
