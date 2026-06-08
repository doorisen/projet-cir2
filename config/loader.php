<?php

$returnPage = $_GET['return'] ?? 'index.php';

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chargement...</title>
    <link rel="stylesheet" href="../assets/css/loader.css">
</head>
<body>

<div class="loading">
    <img src="../assets/img/pikachu.gif" alt="Chargement...">
    <div class="loading-text">
        Populating database
        <span class="dot">.</span>
        <span class="dot">.</span>
        <span class="dot">.</span>
    </div>
</div>


<script>
    const RETURN_PAGE = <?= json_encode($returnPage) ?>;
</script>
<script src="../assets/js/loader.js"></script>

</body>
</html>