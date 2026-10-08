<?php
declare(strict_types=1);

/**
 * Applique les migrations SQL non encore exécutées (database/migrations/*.sql).
 * Idempotent : chaque migration n'est jouée qu'une fois (table schema_migrations).
 *
 * Tolérant : une base installée avec un schema.sql récent contient déjà
 * certaines colonnes. Un ajout de colonne / table / index déjà présent est donc
 * ignoré (et un ALTER multiple est rejoué clause par clause), au lieu de
 * bloquer silencieusement toutes les migrations suivantes.
 */
class Migrator
{
    /** Message d'erreur de la dernière migration en échec (affiché aux administrateurs). */
    public static ?string $error = null;

    /** Codes MySQL/MariaDB signifiant « déjà fait » : sans danger à ignorer. */
    private const ALREADY_DONE = [
        1050, // table déjà existante
        1060, // colonne déjà existante
        1061, // index déjà existant
        1091, // colonne / index à supprimer déjà absent
    ];

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

                $sql = self::stripComments((string) file_get_contents($file));
                foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
                    self::exec($pdo, $stmt);
                }
                Database::query('INSERT INTO schema_migrations (version) VALUES (?)', [$version]);
            }
        } catch (Throwable $e) {
            // On n'interrompt pas l'application, mais l'erreur est affichée (bandeau) et journalisée.
            self::$error = (isset($version) ? "$version : " : '') . $e->getMessage();
            error_log('[PerTec] Migration échouée : ' . self::$error);
        }
    }

    /** Exécute une instruction en ignorant ce qui est déjà en place. */
    private static function exec(PDO $pdo, string $stmt): void
    {
        try {
            $pdo->exec($stmt);
        } catch (PDOException $e) {
            if (!in_array(self::code($e), self::ALREADY_DONE, true)) throw $e;
            // ALTER TABLE avec plusieurs clauses : une colonne existait déjà, les
            // autres n'ont pas été créées (l'ALTER est atomique) → clause par clause.
            if (preg_match('/^ALTER\s+TABLE\s+(`?\w+`?)\s+(.*)$/is', $stmt, $m)) {
                $clauses = self::splitTopLevel($m[2]);
                if (count($clauses) > 1) {
                    foreach ($clauses as $clause) {
                        self::exec($pdo, 'ALTER TABLE ' . $m[1] . ' ' . $clause);
                    }
                }
            }
        }
    }

    private static function code(PDOException $e): int
    {
        return (int) ($e->errorInfo[1] ?? 0);
    }

    /** Retire les commentaires « -- … » (hors chaînes). */
    private static function stripComments(string $sql): string
    {
        $out = '';
        foreach (preg_split('/\R/', $sql) as $line) {
            $inQuote = false;
            for ($i = 0, $n = strlen($line); $i < $n; $i++) {
                if ($line[$i] === "'") $inQuote = !$inQuote;
                if (!$inQuote && substr($line, $i, 3) === '-- ') { $line = substr($line, 0, $i); break; }
            }
            $out .= $line . "\n";
        }
        return $out;
    }

    /** Découpe « a, b(1,2), c » sur les virgules de premier niveau. */
    private static function splitTopLevel(string $s): array
    {
        $parts = [];
        $depth = 0;
        $inQuote = false;
        $buf = '';
        for ($i = 0, $n = strlen($s); $i < $n; $i++) {
            $c = $s[$i];
            if ($c === "'") $inQuote = !$inQuote;
            if (!$inQuote) {
                if ($c === '(') $depth++;
                if ($c === ')') $depth--;
                if ($c === ',' && $depth === 0) { $parts[] = trim($buf); $buf = ''; continue; }
            }
            $buf .= $c;
        }
        $parts[] = trim($buf);
        return array_values(array_filter($parts, fn($p) => $p !== ''));
    }
}
