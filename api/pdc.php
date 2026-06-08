<?php

declare(strict_types=1);

require_once '../config/db.php';
require_once '../helpers/classes/PdcController.php';


$controller = new PdcController($pdo);

$controller->handleRequest(
    $_SERVER['REQUEST_METHOD'],
    $_GET['id'] ?? null
);

?>