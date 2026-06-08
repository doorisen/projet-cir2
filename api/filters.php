<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/classes/FilterManager.php';


$manager = new FilterManager($pdo);

echo json_encode([
    'amenageurs'    => $manager->getAmenageurs(),
    'operateurs'    => $manager->getOperateurs(),
    'departements'  => $manager->getDepartements(),
    'annees'        => $manager->getAnnees(),
    'prises'        => $manager->getPrises(),
    'stations'      => $manager->getStations()
]);

?>