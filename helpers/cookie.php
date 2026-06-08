<?php

declare(strict_types=1);


$action = $_GET['action'] ?? '';

if ($action === 'login') {
    setcookie('irve_admin', 'actif', time() + 3600, '/');   // Crée le cookie 'irve_admin' valide pendant 1 heure
} elseif ($action === 'logout') {
    setcookie('irve_admin', '', time() - 3600, '/');        // Supprime le cookie 'irve_admin'
}

$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $redirect);
exit();

?>