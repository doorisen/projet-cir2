<?php

require_once 'config/try_populate.php';

?>


<!-- Map -->
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte - IRVE Bretagne <?= $isAdmin ? '- Administration' : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="d-flex flex-column min-vh-100 custom-bg <?= $isAdmin ? 'admin-mode' : '' ?>">

<?php include 'helpers/header.php'; ?>

<main class="container-fluid px-4 my-4 flex-grow-1 d-flex flex-column gap-3">
    
    <!-- Filtres -->
    <section class="p-3 rounded-3 custom-card text-light border border-secondary border-opacity-25 shadow-sm">
        <form class="row g-3 align-items-end m-0" id='filter-form'>
            <!-- Filtrage par année -->
            <div class="col-md-4">
                <label class="form-label text-secondary small text-uppercase fw-semibold mb-1">Année</label>
                <select id="filter-year" class="form-select bg-dark text-light border-secondary border-opacity-25">
                    <option value="">Toutes</option>
                </select>
            </div>
            <!-- Filtrage par département -->
            <div class="col-md-4">
                <label class="form-label text-secondary small text-uppercase fw-semibold mb-1">Département</label>
                <select id="filter-department" class="form-select bg-dark text-light border-secondary border-opacity-25">
                    <option value="">Tous</option>
                </select>
            </div>
            <!-- Rafraichissement de la carte -->
            <div class="col-md-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5">Afficher</button>
            </div>
        </form>
    </section>

    <!-- Carte -->
    <section class="map-container">
        <!-- Carte Leaflet -->
        <div id='map' style='height:600px;'></div>
    </section>

</main>

<?php include 'helpers/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="assets/js/filters.js" defer></script>
<script src="assets/js/map.js" defer></script>
</body>
</html>