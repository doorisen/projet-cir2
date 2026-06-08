<?php

declare(strict_types=1);
header('Content-Type: application/json');

require_once '../config/db.php';
require_once '../helpers/classes/StationManager.php';


$stationManager = new StationManager($pdo);

switch ($_SERVER['REQUEST_METHOD']) {
case 'GET':
    $annee          = !empty($_GET['filtre_annee'])         ? (int) $_GET['filtre_annee']   : null;
    $departement    = !empty($_GET['filtre_departement'])   ? $_GET['filtre_departement']   : null;

    echo json_encode(
        $stationManager->getForMap(
            $annee,
            $departement
        ),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
    break;

default:
    http_response_code(405);
    echo json_encode([
        'error' => 'Method not allowed'
    ]);
}

?>