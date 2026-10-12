<?php
/**
 * Statistiques de fréquentation - Pertec.fr
 *
 * Comptage côté serveur, sans cookie et sans donnée personnelle :
 * seuls des totaux par jour sont enregistrés (pages vues, visites,
 * provenance, demandes de devis et d'estimation). Aucune IP n'est stockée.
 *
 * Fichiers : admin/stats/AAAA-MM.json (bloqués en accès direct par admin/.htaccess)
 */

const STATS_NOSTATS_COOKIE = 'pertec_nostats';

function stats_dir(): string {
    return dirname(__DIR__) . '/admin/stats';
}

/**
 * Applique $update aux statistiques du jour, sous verrou de fichier.
 */
function stats_update(callable $update): void {
    $dir = stats_dir();
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return;
    }

    $fp = @fopen($dir . '/' . date('Y-m') . '.json', 'c+');
    if (!$fp) {
        return;
    }

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(stream_get_contents($fp), true) ?: [];
        $day = date('Y-m-d');
        $data[$day] = $update($data[$day] ?? []);

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

/**
 * Robots, aperçus de liens, préchargements et visites du propriétaire ne comptent pas.
 */
function stats_should_skip(): bool {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') return true;
    if (!empty($_COOKIE[STATS_NOSTATS_COOKIE])) return true;
    if (($_SERVER['REDIRECT_STATUS'] ?? '') === '404') return true;

    $purpose = strtolower(($_SERVER['HTTP_SEC_PURPOSE'] ?? '') . ($_SERVER['HTTP_PURPOSE'] ?? ''));
    if (strpos($purpose, 'prefetch') !== false) return true;

    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ($ua === '') return true;
    return (bool) preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit|whatsapp|curl|wget|python|java\/|go-http|headless|lighthouse|pingdom|uptime|monitor/i', $ua);
}

/**
 * Chemin lisible de la page : /services.php -> /services, /index.php -> /
 */
function stats_page_path(): string {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $path = preg_replace('/\.(php|html)$/', '', $path);
    $path = preg_replace('#/index$#', '/', $path);
    return substr($path, 0, 100) ?: '/';
}

/**
 * Provenance d'une visite, ou null si le visiteur navigue déjà sur le site.
 */
function stats_source(): ?string {
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if ($referer === '') return 'Accès direct';

    $host = strtolower(parse_url($referer, PHP_URL_HOST) ?: '');
    $host = preg_replace('/^www\./', '', $host);
    $ownHost = preg_replace(['/^www\./', '/:\d+$/'], '', strtolower($_SERVER['HTTP_HOST'] ?? ''));

    if ($host === '') return 'Accès direct';
    if ($host === $ownHost || $host === 'pertec.fr') return null;
    if (preg_match('/(^|\.)google\./', $host)) return 'Google';
    if (preg_match('/(^|\.)(bing|qwant|duckduckgo|ecosia|yahoo|yandex|lilo|startpage)\./', $host)) return 'Autres moteurs';
    if (preg_match('/(^|\.)(facebook|fb|instagram|linkedin|lnkd|twitter|x|t|pinterest|tiktok|youtube|whatsapp)\.(com|co|in|me)$/', $host)) return 'Réseaux sociaux';
    return substr($host, 0, 60);
}

/**
 * À appeler en haut de chaque page publique.
 */
function stats_track_pageview(): void {
    if (stats_should_skip()) return;

    $page = stats_page_path();
    $source = stats_source();

    stats_update(function (array $day) use ($page, $source) {
        $day['vues'] = ($day['vues'] ?? 0) + 1;
        $day['pages'][$page] = ($day['pages'][$page] ?? 0) + 1;
        if ($source !== null) {
            $day['visites'] = ($day['visites'] ?? 0) + 1;
            $day['sources'][$source] = ($day['sources'][$source] ?? 0) + 1;
            $day['entrees'][$page] = ($day['entrees'][$page] ?? 0) + 1;
        }
        return $day;
    });
}

/**
 * Compte une conversion : 'devis' ou 'estimations'.
 */
function stats_track_event(string $event): void {
    if (!empty($_COOKIE[STATS_NOSTATS_COOKIE])) return;

    stats_update(function (array $day) use ($event) {
        $day[$event] = ($day[$event] ?? 0) + 1;
        return $day;
    });
}

/**
 * Charge les statistiques jour par jour entre deux dates (incluses).
 * @return array<string, array> indexé par 'AAAA-MM-JJ'
 */
function stats_load(DateTimeImmutable $from, DateTimeImmutable $to): array {
    $result = [];
    $month = $from->modify('first day of this month');
    while ($month <= $to) {
        $file = stats_dir() . '/' . $month->format('Y-m') . '.json';
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true) ?: [];
            foreach ($data as $day => $values) {
                if ($day >= $from->format('Y-m-d') && $day <= $to->format('Y-m-d')) {
                    $result[$day] = $values;
                }
            }
        }
        $month = $month->modify('+1 month');
    }
    return $result;
}
