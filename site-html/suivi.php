<?php
/**
 * Réception des événements du script de suivi (js/suivi.js) - Pertec.fr
 */
require_once __DIR__ . '/includes/stats.php';
require_once __DIR__ . '/includes/suivi.php';

header('X-Robots-Tag: noindex');

$corps = file_get_contents('php://input', false, null, 0, 2048);
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $corps && empty($_COOKIE[STATS_NOSTATS_COOKIE]) && !suivi_refuse()) {
    $donnees = json_decode($corps, true);
    if (is_array($donnees)) {
        foreach (array_slice(isset($donnees['k']) ? [$donnees] : $donnees, 0, 20) as $e) {
            if (is_array($e)) suivi_evenement($e);
        }
    }
}

http_response_code(204);
