<?php

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/classes/PdcManager.php';


try {
    $manager = new PdcManager($pdo);

    $results = $manager->search(
        $_GET['amenageur'] ?? null,
        $_GET['prise'] ?? null,
        $_GET['departement'] ?? null,
        $_GET['station'] ?? null
    );

    echo json_encode([
        'success' => true,
        'data' => $results
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

