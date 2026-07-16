<?php
declare(strict_types=1);

/** Échappement HTML. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Construit une URL en tenant compte du base_url. */
function url(string $path = ''): string
{
    $base = rtrim((string) App::config('app')['base_url'], '/');
    $path = '/' . ltrim($path, '/');
    return $base . ($path === '/' ? '/' : rtrim($path, '/'));
}

/** Redirection interne. */
function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

/** Formatage monétaire € (français). */
function euros($amount): string
{
    return number_format((float) $amount, 2, ',', ' ') . ' €';
}

/** Formatage date jj/mm/aaaa. */
function fdate(?string $date): string
{
    if (!$date) return '—';
    $ts = strtotime($date);
    return $ts ? date('d/m/Y', $ts) : e($date);
}

/** Nom du mois en français. */
function moisFr(int $m): string
{
    static $noms = [1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
    return $noms[$m] ?? (string) $m;
}

/**
 * Libellé de la durée d'un bail déduit des dates de début/fin (fin incluse).
 * Renvoie « 3 ans », « 1 an », « 9 mois »… ou null si la durée n'est pas
 * un nombre rond d'années ou de mois (on évite alors d'afficher un chiffre
 * trompeur). Une tolérance de quelques jours absorbe les fins de mois/années.
 */
function dureeBail(?string $start, ?string $end): ?string
{
    if (!$start || !$end) return null;
    $s = new DateTime($start);
    $e = new DateTime($end);
    if ($e <= $s) return null;

    $days = (int) $s->diff($e)->days + 1; // occupation, fin incluse

    $years = (int) round($days / 365.25);
    if ($years >= 1 && abs($days - $years * 365.25) <= 5) {
        return $years . ($years > 1 ? ' ans' : ' an');
    }
    $months = (int) round($days / 30.44);
    if ($months >= 1 && abs($days - $months * 30.44) <= 3) {
        return $months . ' mois';
    }
    return null;
}

/**
 * Trimestre IRL de référence en vigueur à une date de signature donnée.
 * L'INSEE publie l'IRL d'un trimestre au cours du trimestre suivant : l'indice
 * connu à la signature est donc celui du trimestre PRÉCÉDENT.
 * Réf. art. 17-1 de la loi n° 89-462 du 6 juillet 1989 (révision annuelle du loyer).
 *
 * @return array{quarter:int,year:int}
 */
function irlReference(?string $signatureDate): array
{
    $ts = $signatureDate ? strtotime($signatureDate) : time();
    if ($ts === false) $ts = time();
    $month = (int) date('n', $ts);
    $year  = (int) date('Y', $ts);
    $quarter = (int) ceil($month / 3);   // trimestre de la signature (1..4)
    $quarter--;                          // trimestre précédent (dernier publié)
    if ($quarter < 1) { $quarter = 4; $year--; }
    return ['quarter' => $quarter, 'year' => $year];
}

/** Entier écrit en toutes lettres (français), pour 0 à 999 999 999. */
function nombreEnLettres(int $n): string
{
    if ($n < 0) return 'moins ' . nombreEnLettres(-$n);
    if ($n === 0) return 'zéro';

    $u = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix',
        'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];

    $below100 = function (int $n) use ($u): string {
        if ($n < 20) return $u[$n];
        $d = intdiv($n, 10);
        $r = $n % 10;
        $map = [2 => 'vingt', 3 => 'trente', 4 => 'quarante', 5 => 'cinquante', 6 => 'soixante'];
        if ($d <= 6) {
            if ($r === 0) return $map[$d];
            if ($r === 1) return $map[$d] . '-et-un';
            return $map[$d] . '-' . $u[$r];
        }
        if ($d === 7) return $r === 1 ? 'soixante-et-onze' : 'soixante-' . $u[10 + $r];
        if ($d === 8) return $r === 0 ? 'quatre-vingts' : 'quatre-vingt-' . $u[$r];
        return 'quatre-vingt-' . $u[10 + $r]; // 90-99
    };

    $below1000 = function (int $n) use ($u, $below100): string {
        if ($n < 100) return $below100($n);
        $c = intdiv($n, 100);
        $r = $n % 100;
        if ($r === 0) return $c === 1 ? 'cent' : $u[$c] . ' cents';
        return ($c === 1 ? 'cent' : $u[$c] . ' cent') . ' ' . $below100($r);
    };

    $parts = [];
    $millions = intdiv($n, 1000000);
    $milliers = intdiv($n % 1000000, 1000);
    $reste    = $n % 1000;
    if ($millions > 0) $parts[] = $millions === 1 ? 'un million' : $below1000($millions) . ' millions';
    if ($milliers > 0) $parts[] = $milliers === 1 ? 'mille' : $below1000($milliers) . ' mille';
    if ($reste > 0)    $parts[] = $below1000($reste);
    return implode(' ', $parts);
}

/** Montant en euros écrit en toutes lettres (ex. « quatre cent soixante-cinq euros »). */
function eurosLettres($amount): string
{
    $amount = (float) $amount;
    $euros  = (int) floor($amount);
    $cents  = (int) round(($amount - $euros) * 100);
    $txt = nombreEnLettres($euros) . ' euro' . ($euros > 1 ? 's' : '');
    if ($cents > 0) {
        $txt .= ' et ' . nombreEnLettres($cents) . ' centime' . ($cents > 1 ? 's' : '');
    }
    return $txt;
}

/** Valeur d'un champ POST. */
function post(string $key, $default = null)
{
    return $_POST[$key] ?? $default;
}

/** Valeur numérique nettoyée depuis un formulaire. */
function num($v): float
{
    if ($v === null || $v === '') return 0.0;
    return (float) str_replace([' ', ','], ['', '.'], (string) $v);
}

/** Jeton CSRF. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    if (($_POST['_csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        http_response_code(419);
        exit('Jeton de sécurité invalide. Rechargez la page.');
    }
}

/** Message flash (une seule lecture). */
function flash(?string $msg = null, string $type = 'success')
{
    if ($msg !== null) {
        $_SESSION['flash'][] = ['msg' => $msg, 'type' => $type];
        return null;
    }
    $out = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $out;
}

/** Rend un gabarit et retourne le HTML (sans layout). */
function render_template(string $template, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    return (string) ob_get_clean();
}

/** Rendu d'une vue avec layout. */
function view(string $template, array $data = [], ?string $layout = 'layout'): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    $content = ob_get_clean();
    if ($layout === null) {
        echo $content;
        return;
    }
    require __DIR__ . '/../templates/' . $layout . '.php';
}
