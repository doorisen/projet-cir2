<?php

declare(strict_types=1);


define('DB_HOST', 'localhost');
define('DB_NAME', 'irve_bretagne');
define('DB_USER', 'irve');
define('DB_PASS', 'irve');

define('DB_CHARSET', 'utf8mb4');

define('DEFAULT_SEARCH_RADIUS_KM', 10);

$isAdmin = isset($_COOKIE['irve_admin']) && $_COOKIE['irve_admin'] === 'actif';

?>