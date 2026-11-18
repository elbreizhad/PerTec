<?php
require_once __DIR__ . '/data.php';
/**
 * Parcours des visiteurs et détection des robots - Pertec.fr
 *
 * Chaque page vue, clic, défilement et temps passé est noté dans un journal
 * par jour (pertec-data/stats/journal/AAAA-MM-JJ.jsonl), hors du dossier public.
 *
 * Pas de cookie et pas d'adresse IP enregistrée : une visite est reconnue par
 * une empreinte (IP + navigateur) hachée avec une clé qui change chaque jour,
 * impossible à relier d'un jour à l'autre. Les navigateurs qui demandent à ne
 * pas être suivis (Do Not Track, Global Privacy Control, ou bouton d'opposition de la
 * page Confidentialité, cookie pertec_nosuivi) ne sont pas journalisés.
 * Journaux conservés SUIVI_JOURS_CONSERVES jours.
 */

const SUIVI_JOURS_CONSERVES = 90;

/** Robots connus : nom => [motif dans le navigateur, catégorie] */
const SUIVI_ROBOTS = [
    'Googlebot' => ['googlebot|google-inspectiontool|googleother|storebot-google|adsbot-google|google-site-verification', 'Moteurs de recherche'],
    'Bingbot' => ['bingbot|bingpreview|msnbot', 'Moteurs de recherche'],
    'Applebot' => ['applebot', 'Moteurs de recherche'],
    'Qwant' => ['qwant', 'Moteurs de recherche'],
    'YandexBot' => ['yandex', 'Moteurs de recherche'],
    'DuckDuckGo' => ['duckduckbot|duckassistbot', 'Moteurs de recherche'],
    'Baidu' => ['baiduspider', 'Moteurs de recherche'],
    'PetalBot' => ['petalbot', 'Moteurs de recherche'],
    'Seznam' => ['seznambot', 'Moteurs de recherche'],
    'ChatGPT' => ['gptbot|chatgpt-user|oai-searchbot', 'IA'],
    'Claude' => ['claudebot|claude-user|claude-searchbot|anthropic-ai', 'IA'],
    'Perplexity' => ['perplexitybot|perplexity-user', 'IA'],
    'Meta IA' => ['meta-externalagent|meta-externalfetcher', 'IA'],
    'Amazonbot' => ['amazonbot', 'IA'],
    'Bytespider' => ['bytespider', 'IA'],
    'Common Crawl' => ['ccbot', 'IA'],
    'Mistral' => ['mistralai-user', 'IA'],
    'Autres IA' => ['cohere-ai|diffbot|youbot|deepseekbot|timpibot|ai2bot', 'IA'],
    'Ahrefs' => ['ahrefsbot|ahrefssiteaudit', 'Outils SEO'],
    'Semrush' => ['semrushbot|siteauditbot', 'Outils SEO'],
    'Majestic' => ['mj12bot', 'Outils SEO'],
    'Moz' => ['dotbot|rogerbot', 'Outils SEO'],
    'Screaming Frog' => ['screaming frog', 'Outils SEO'],
    'Autres outils SEO' => ['blexbot|dataforseobot|serpstatbot|barkrowler|seokicks|linkdexbot|awariobot', 'Outils SEO'],
    'Facebook' => ['facebookexternalhit|facebookcatalog', 'Aperçus de liens'],
    'LinkedIn' => ['linkedinbot', 'Aperçus de liens'],
    'WhatsApp' => ['whatsapp', 'Aperçus de liens'],
    'X / Twitter' => ['twitterbot', 'Aperçus de liens'],
    'Autres aperçus' => ['slackbot|discordbot|telegrambot|pinterestbot|skypeuripreview|redditbot|snapchat|embedly', 'Aperçus de liens'],
    'Lighthouse / PageSpeed' => ['chrome-lighthouse|pagespeed|gtmetrix', 'Surveillance'],
    'Surveillance' => ['uptimerobot|pingdom|statuscake|site24x7|betteruptime|uptime-kuma|monitor', 'Surveillance'],
    'Navigateur automatisé' => ['headlesschrome|phantomjs|puppeteer|playwright|selenium', 'Scripts'],
    'Scripts' => ['curl|wget|python|go-http-client|java\/|okhttp|axios|node-fetch|libwww|scrapy|httpclient|guzzle|^$', 'Scripts'],
];

/** Adresses typiques des scanners de failles (le site n'en a aucune). */
const SUIVI_MOTIF_SCAN = '#(wp-|wordpress|xmlrpc|\.env|\.git|phpmyadmin|/vendor/|\.aws|\.sql|\.bak|backup|/cgi-bin|eval-stdin|boaform|hnap1|\.aspx?$|/shell|/admin\.php|config\.(json|ya?ml|php\.)|\.ds_store|/\.well-known/(?!acme))#i';

function suivi_dir(): string {
    $dir = pertec_data_subdir('stats') . '/journal';
    if (!is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }
    return $dir;
}

/**
 * Le visiteur demande-t-il à ne pas être suivi ?
 */
function suivi_refuse(): bool {
    return ($_SERVER['HTTP_DNT'] ?? '') === '1' || ($_SERVER['HTTP_SEC_GPC'] ?? '') === '1'
        || !empty($_COOKIE['pertec_nosuivi']);
}

/**
 * Nom et catégorie du robot d'après le navigateur annoncé, ou null.
 */
function suivi_robot(string $ua): ?array {
    foreach (SUIVI_ROBOTS as $nom => [$motif, $categorie]) {
        if (preg_match('#' . $motif . '#i', $ua)) {
            return [$nom, $categorie];
        }
    }
    if (preg_match('#([a-z0-9_-]*(bot|crawler|spider))#i', $ua, $m) || preg_match('#(slurp|crawl)#i', $ua, $m)) {
        return [ucfirst(substr($m[1], 0, 30)), 'Autres robots'];
    }
    return null;
}

function suivi_appareil(string $ua): string {
    if (preg_match('#ipad|tablet|kindle|silk|(android(?!.*mobile))#i', $ua)) return 'Tablette';
    if (preg_match('#mobi|iphone|ipod|android#i', $ua)) return 'Mobile';
    return 'Ordinateur';
}

function suivi_navigateur(string $ua): string {
    foreach (['Edg/' => 'Edge', 'OPR/' => 'Opera', 'SamsungBrowser' => 'Samsung', 'Firefox/' => 'Firefox', 'FxiOS' => 'Firefox', 'CriOS' => 'Chrome', 'Chrome/' => 'Chrome', 'Safari/' => 'Safari'] as $motif => $nom) {
        if (stripos($ua, $motif) !== false) return $nom;
    }
    return 'Autre';
}

/**
 * Identifiant de visite du jour : empreinte hachée avec une clé quotidienne.
 */
function suivi_visiteur(): string {
    static $id = null;
    if ($id !== null) return $id;

    $fichier = suivi_dir() . '/cle.json';
    $jour = date('Y-m-d');
    $cle = json_decode((string) @file_get_contents($fichier), true);
    if (($cle['jour'] ?? '') !== $jour) {
        $cle = ['jour' => $jour, 'cle' => bin2hex(random_bytes(16))];
        @file_put_contents($fichier, json_encode($cle), LOCK_EX);
    }
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    return $id = substr(hash_hmac('sha256', $ip . '|' . $ua, $cle['cle']), 0, 12);
}

/**
 * Ajoute une ligne au journal du jour ; purge les vieux journaux au premier passage du jour.
 */
function suivi_ecrire(array $ligne): void {
    $dir = suivi_dir();
    $fichier = $dir . '/' . date('Y-m-d') . '.jsonl';
    if (!is_file($fichier)) {
        $limite = date('Y-m-d', strtotime('-' . SUIVI_JOURS_CONSERVES . ' days'));
        foreach (glob($dir . '/*.jsonl') ?: [] as $ancien) {
            if (basename($ancien, '.jsonl') < $limite) @unlink($ancien);
        }
    }
    $ligne = ['t' => time(), 'v' => suivi_visiteur()] + $ligne;
    @file_put_contents($fichier, json_encode($ligne, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Note la page vue (robots compris). Renvoie l'identifiant de la page pour le
 * script de suivi, ou null si le script ne doit pas être chargé.
 */
function suivi_page(string $page, ?string $source): ?string {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') return null;
    if (!empty($_COOKIE[STATS_NOSTATS_COOKIE])) return null;
    $purpose = strtolower(($_SERVER['HTTP_SEC_PURPOSE'] ?? '') . ($_SERVER['HTTP_PURPOSE'] ?? ''));
    if (strpos($purpose, 'prefetch') !== false) return null;

    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250);
    $robot = suivi_robot($ua);
    if ($robot === null && suivi_refuse()) return null;

    $statut = (int) (($_SERVER['REDIRECT_STATUS'] ?? '') === '404' ? 404 : (http_response_code() ?: 200));
    $referer = strtolower(preg_replace('/^www\./', '', (string) parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST)));
    $pv = bin2hex(random_bytes(5));

    suivi_ecrire([
        'k' => 'page',
        'p' => $pv,
        'u' => $page,
        'q' => substr((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY), 0, 80),
        's' => $statut,
        'src' => $source,
        'ref' => substr($referer, 0, 60),
        'ua' => $ua,
        'd' => suivi_appareil($ua),
        'n' => suivi_navigateur($ua),
        'l' => empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? 0 : 1,
        'b' => $robot[0] ?? null,
        'c' => $robot[1] ?? null,
    ]);

    return $robot === null ? $pv : null;
}

/**
 * Événement envoyé par le navigateur (js/suivi.js) : 'js', 'clic' ou 'fin'.
 */
function suivi_evenement(array $e): void {
    $pv = (string) ($e['p'] ?? '');
    $k = (string) ($e['k'] ?? '');
    if (!preg_match('/^[a-f0-9]{10}$/', $pv) || !in_array($k, ['js', 'clic', 'fin', 'saisie'], true)) return;

    $propre = fn($s, $n) => mb_substr(trim(preg_replace('/\s+/u', ' ', (string) $s)), 0, $n);
    $ligne = ['k' => $k, 'p' => $pv];
    if ($k === 'js') {
        $ligne['w'] = min(10000, max(0, (int) ($e['w'] ?? 0)));
        $ligne['wd'] = !empty($e['wd']) ? 1 : 0;
    } elseif ($k === 'saisie') {
        $ligne['x'] = $propre($e['x'] ?? '', 60);
    } elseif ($k === 'clic') {
        $ligne['x'] = $propre($e['x'] ?? '', 80);
        $ligne['h'] = $propre($e['h'] ?? '', 120);
    } else {
        $ligne['sc'] = min(100, max(0, (int) ($e['sc'] ?? 0)));
        $ligne['du'] = min(7200, max(0, (int) ($e['du'] ?? 0)));
    }
    suivi_ecrire($ligne);
}

/**
 * Demande de devis ou d'estimation envoyée pendant la visite.
 */
function suivi_conversion(string $type): void {
    if (!empty($_COOKIE[STATS_NOSTATS_COOKIE])) return;
    suivi_ecrire(['k' => 'conv', 'x' => $type]);
}

/**
 * Lignes du journal entre deux dates (incluses).
 */
function suivi_charger(DateTimeImmutable $debut, DateTimeImmutable $fin): array {
    $lignes = [];
    for ($d = $debut; $d <= $fin; $d = $d->modify('+1 day')) {
        $fichier = suivi_dir() . '/' . $d->format('Y-m-d') . '.jsonl';
        if (!is_file($fichier)) continue;
        foreach (file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $l) {
            $ligne = json_decode($l, true);
            if (is_array($ligne)) $lignes[] = $ligne;
        }
    }
    return $lignes;
}

/**
 * Regroupe le journal en visites (même visiteur, moins de 30 min entre deux actions)
 * et classe chaque visite : humain, robot connu ou robot suspect.
 */
function suivi_visites(array $lignes): array {
    usort($lignes, fn($a, $b) => $a['t'] <=> $b['t']);

    $pages = [];        // pv => index de visite / de page
    $dernier = [];      // visiteur => index de la visite en cours
    $visites = [];

    foreach ($lignes as $l) {
        $v = $l['v'] ?? '';
        if (($l['k'] ?? '') === 'page') {
            $i = $dernier[$v] ?? null;
            if ($i === null || $l['t'] - $visites[$i]['fin'] > 1800) {
                $i = count($visites);
                $visites[$i] = ['visiteur' => $v, 'debut' => $l['t'], 'fin' => $l['t'], 'pages' => [], 'js' => false, 'webdriver' => false,
                    'source' => null, 'appareil' => $l['d'] ?? '', 'navigateur' => $l['n'] ?? '', 'ua' => $l['ua'] ?? '',
                    'langue' => (bool) ($l['l'] ?? 1), 'robot' => $l['b'] ?? null, 'categorie' => $l['c'] ?? null, 'conversions' => [], 'tConversion' => null, 'largeur' => null];
                $dernier[$v] = $i;
            }
            $visites[$i]['fin'] = $l['t'];
            if ($visites[$i]['source'] === null && !empty($l['src'])) $visites[$i]['source'] = $l['src'];
            if (!empty($l['b'])) { $visites[$i]['robot'] = $l['b']; $visites[$i]['categorie'] = $l['c']; }
            $visites[$i]['pages'][] = ['t' => $l['t'], 'u' => $l['u'], 'q' => $l['q'] ?? '', 's' => $l['s'] ?? 200, 'clics' => [], 'saisies' => [], 'sc' => null, 'du' => null];
            $pages[$l['p']] = [$i, count($visites[$i]['pages']) - 1];
            continue;
        }

        if (($l['k'] ?? '') === 'conv') {
            if (isset($dernier[$v])) {
                $visites[$dernier[$v]]['conversions'][] = $l['x'] ?? '';
                $visites[$dernier[$v]]['tConversion'] ??= $l['t'];
            }
            continue;
        }

        if (!isset($pages[$l['p'] ?? ''])) continue;
        [$i, $j] = $pages[$l['p']];
        $visites[$i]['fin'] = max($visites[$i]['fin'], $l['t']);
        if ($l['k'] === 'js') {
            $visites[$i]['js'] = true;
            $visites[$i]['webdriver'] = $visites[$i]['webdriver'] || !empty($l['wd']);
            $visites[$i]['largeur'] = $l['w'] ?? null;
        } elseif ($l['k'] === 'saisie') {
            $visites[$i]['pages'][$j]['saisies'][] = ['t' => $l['t'], 'x' => $l['x'] ?? ''];
        } elseif ($l['k'] === 'clic') {
            $visites[$i]['pages'][$j]['clics'][] = ['t' => $l['t'], 'x' => $l['x'] ?? '', 'h' => $l['h'] ?? ''];
        } elseif ($l['k'] === 'fin') {
            $visites[$i]['pages'][$j]['sc'] = max($visites[$i]['pages'][$j]['sc'] ?? 0, $l['sc'] ?? 0);
            $visites[$i]['pages'][$j]['du'] = ($visites[$i]['pages'][$j]['du'] ?? 0) + ($l['du'] ?? 0);
        }
    }

    foreach ($visites as &$visite) {
        $visite['raisons'] = [];
        if ($visite['robot']) {
            $visite['type'] = 'robot';
            continue;
        }
        $nb = count($visite['pages']);
        foreach ($visite['pages'] as $p) {
            if (preg_match(SUIVI_MOTIF_SCAN, $p['u'])) { $visite['raisons'][] = 'Cherche des failles'; break; }
        }
        if ($visite['webdriver']) $visite['raisons'][] = 'Navigateur piloté par un programme';
        if (!$visite['js']) $visite['raisons'][] = 'N\'exécute pas JavaScript';
        if (!$visite['langue']) $visite['raisons'][] = 'Aucune langue déclarée';
        if ($nb >= 10 && ($visite['fin'] - $visite['debut']) / $nb < 2) $visite['raisons'][] = 'Navigation trop rapide';
        $visite['type'] = $visite['raisons'] ? 'suspect' : 'humain';
    }
    unset($visite);

    return $visites;
}

/**
 * Entonnoirs : chaque étape ne compte que les visites qui ont franchi les précédentes.
 * Une étape est une fonction (visite) => bool.
 */
function suivi_entonnoirs(): array {
    $aVu = fn(string $motif) => fn(array $v) => (bool) array_filter($v['pages'], fn($p) => preg_match($motif, $p['u']));
    $aClique = fn(callable $test) => fn(array $v) => (bool) array_filter($v['pages'], fn($p) => array_filter($p['clics'], $test));
    $versDevis = fn($c) => in_array($c['h'], ['/contact', 'tel:', 'mailto:'], true) || str_starts_with($c['h'], '/contact');
    $demande = fn(array $v) => in_array('devis', $v['conversions'], true);

    return [
        'Demande de devis' => [
            'Visite du site' => fn(array $v) => true,
            'A ouvert la page Contact' => $aVu('#^/contact$#'),
            'A commencé à remplir le formulaire' => fn(array $v) => (bool) array_filter($v['pages'], fn($p) => $p['saisies']),
            'A cliqué « Envoyer »' => $aClique(fn($c) => $c['h'] === 'formulaire'),
            'Demande reçue' => $demande,
        ],
        'Tarifs' => [
            'A ouvert la page Tarifs' => $aVu('#^/tarifs$#'),
            'A cliqué vers le devis ou le contact' => $aClique($versDevis),
            'Demande reçue' => $demande,
        ],
        'Articles Conseils' => [
            'A ouvert un article' => $aVu('#^/conseils/.+#'),
            'L\'a lu jusqu\'au bout' => fn(array $v) => (bool) array_filter($v['pages'], fn($p) => preg_match('#^/conseils/.+#', $p['u']) && ($p['sc'] ?? 0) >= 90),
            'A cliqué vers le devis, les tarifs ou le contact' => $aClique(fn($c) => $versDevis($c) || str_starts_with($c['h'], '/tarifs')),
            'Demande reçue' => $demande,
        ],
    ];
}

/**
 * Comptage des entonnoirs sur des visites humaines.
 */
function suivi_entonnoirs_compter(array $visites): array {
    $resultats = [];
    foreach (suivi_entonnoirs() as $nom => $etapes) {
        $restantes = $visites;
        foreach ($etapes as $etape => $test) {
            $restantes = array_values(array_filter($restantes, $test));
            $resultats[$nom][$etape] = count($restantes);
        }
    }
    return $resultats;
}

