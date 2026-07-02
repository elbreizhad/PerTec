<?php
declare(strict_types=1);

/**
 * Applique les migrations SQL non encore exécutées (database/migrations/*.sql).
 * Idempotent : chaque migration n'est jouée qu'une fois (table schema_migrations).
 */
class Migrator
{
    public static function run(): void
    {
        try {
            $pdo = Database::pdo();
            $pdo->exec(
                'CREATE TABLE IF NOT EXISTS schema_migrations (
                    version VARCHAR(100) NOT NULL,
                    applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (version)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
            );

            $applied = array_column(
                Database::all('SELECT version FROM schema_migrations'),
                'version'
            );

            $dir = __DIR__ . '/../database/migrations';
            if (!is_dir($dir)) return;
            $files = glob($dir . '/*.sql') ?: [];
            sort($files);

            foreach ($files as $file) {
                $version = basename($file, '.sql');
                if (in_array($version, $applied, true)) continue;

                $sql = file_get_contents($file);
                foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
                    if ($stmt !== '') $pdo->exec($stmt);
                }
                Database::query('INSERT INTO schema_migrations (version) VALUES (?)', [$version]);
            }
        } catch (Throwable $e) {
            // On n'interrompt pas l'application si une migration échoue.
            error_log('[PerTec] Migration échouée : ' . $e->getMessage());
        }
    }
}
