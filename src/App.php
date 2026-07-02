<?php
declare(strict_types=1);

/**
 * Conteneur applicatif : configuration, bootstrap, routeur minimal.
 */
class App
{
    private static array $config = [];
    private static array $routes = [];

    public static function boot(): void
    {
        $configFile = __DIR__ . '/../config/config.php';
        if (!is_file($configFile)) {
            // Pas encore installé : on ne charge que le nécessaire.
            self::$config = require __DIR__ . '/../config/config.example.php';
            return;
        }
        self::$config = require $configFile;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('pertec_sess');
            session_start();
        }
    }

    public static function isInstalled(): bool
    {
        return is_file(__DIR__ . '/../config/config.php');
    }

    public static function config(string $key)
    {
        return self::$config[$key] ?? null;
    }

    public static function route(string $method, string $pattern, callable $handler): void
    {
        self::$routes[] = [strtoupper($method), $pattern, $handler];
    }

    public static function get(string $p, callable $h): void  { self::route('GET', $p, $h); }
    public static function post(string $p, callable $h): void { self::route('POST', $p, $h); }

    /** Chemin courant relatif au base_url. */
    public static function currentPath(): string
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $base = rtrim((string) (self::$config['app']['base_url'] ?? ''), '/');
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . trim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path   = self::currentPath();

        foreach (self::$routes as [$m, $pattern, $handler]) {
            if ($m !== $method) continue;
            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                echo $handler($params) ?? '';
                return;
            }
        }

        http_response_code(404);
        view('error', ['title' => 'Page introuvable', 'message' => 'Cette page n\'existe pas.']);
    }
}
