<?php

declare(strict_types=1);

require_once 'db.php';

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM STATION
");

$nbStations = (int) $stmt->fetchColumn();

if ($nbStations === 0) {
    $currentPage = urlencode($_SERVER['REQUEST_URI']);

    header("Location: /config/loader.php?return={$currentPage}");
    exit;
}

?>