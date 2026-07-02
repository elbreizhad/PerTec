<?php
declare(strict_types=1);

/**
 * Authentification mono-utilisateur.
 */
class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $user = Database::one('SELECT * FROM users WHERE username = ?', [$username]);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']   = (int) $user['id'];
            $_SESSION['username']  = $user['username'];
            session_regenerate_id(true);
            return true;
        }
        return false;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;
        return Database::one('SELECT id, username FROM users WHERE id = ?', [$_SESSION['user_id']]);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /** À appeler en tête des routes protégées. */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/login');
        }
    }
}
