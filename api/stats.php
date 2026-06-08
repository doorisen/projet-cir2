<?php

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/classes/StatsManager.php';
require_once __DIR__ . '/../helpers/classes/FilterManager.php';


try {
    $statsManager = new StatsManager($pdo);
    $filterManager = new FilterManager($pdo);
    echo json_encode([
        'success' => true,
        'stats' => $statsManager->getGlobalStats(),
        'years' => $statsManager->getPdcByYear(),
        'departements' => $filterManager->getDepartements()
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

?>