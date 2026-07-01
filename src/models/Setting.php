<?php
declare(strict_types=1);

/** Réglages / coordonnées du bailleur (clé/valeur). */
class Setting
{
    public static function all(): array
    {
        $rows = Database::all('SELECT `key`, `value` FROM settings');
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = $r['value'];
        }
        return $out;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $row = Database::one('SELECT `value` FROM settings WHERE `key` = ?', [$key]);
        return $row ? $row['value'] : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        Database::query(
            'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
            [$key, $value]
        );
    }

    public static function saveMany(array $pairs): void
    {
        foreach ($pairs as $k => $v) {
            self::set($k, $v);
        }
    }
}
