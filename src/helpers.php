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
