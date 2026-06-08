<?php

require_once 'config/try_populate.php';

?>


<!-- Accueil -->
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> IRVE Bretagne <?= $isAdmin ? '- Administration' : '' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<script src="assets/js/helpers.js" defer></script>
<script src="assets/js/stats.js" defer></script>
<body class="d-flex flex-column min-vh-100 custom-bg <?= $isAdmin ? 'admin-mode' : '' ?>">

<?php include 'helpers/header.php'; ?>

<main class="container my-5 flex-grow-1 d-flex flex-column gap-4">
    
    <!-- Message de bienvenue -->
    <section class="p-4 rounded-3 custom-card text-light border border-secondary border-opacity-25 shadow-sm">
        <p class="mb-0">Bienvenue sur le portail des points de recharge pour véhicules électriques en Bretagne. Consultez, filtrez et localisez les bornes IRVE sur tout le territoire.</p>
    </section>

    <!-- Stats - Général -->
    <section class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 custom-card border-secondary border-opacity-25 shadow-sm text-light">
                <div class="card-body d-flex flex-column">
                    <span class="text-secondary small text-uppercase fw-semibold mb-2">Points de recharge</span>
                    <span id="nb-pdc" class="fs-1 fw-bold lh-1 mb-1">...</span>
                    <span class="text-secondary small mt-auto">enregistrements en base</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 custom-card border-secondary border-opacity-25 shadow-sm text-light">
                <div class="card-body d-flex flex-column">
                    <span class="text-secondary small text-uppercase fw-semibold mb-2">Aménageurs</span>
                    <span id="nb-amenageurs" class="fs-1 fw-bold lh-1 mb-1">...</span>
                    <span class="text-secondary small mt-auto">opérateurs distincts</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 custom-card border-secondary border-opacity-25 shadow-sm text-light">
                <div class="card-body d-flex flex-column">
                    <span class="text-secondary small text-uppercase fw-semibold mb-2">Départements</span>
                    <span id="nb-departements" class="fs-1 fw-bold lh-1 mb-1">...</span>
                    <span id="departements-list" class="text-secondary small mt-auto">...</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats - Pdc par année -->
    <section class="mt-2">
        <h5 class="text-white fw-semibold mb-3">Points installés par année</h5>
        <div id="year-chart" class="mock-chart d-flex align-items-end gap-2 pb-2"></div>
    </section>

    <!-- Boutons -->
    <section class="d-flex gap-3 mt-3">
        <button class="btn btn-outline-secondary custom-action-btn text-white d-flex align-items-center gap-2 rounded-pill px-4 py-2">
            <a class="nav-link" href="search.php">
                <i class="bi bi-search"></i> Rechercher une borne
            </a>
        </button>
        <button class="btn btn-outline-secondary custom-action-btn text-white d-flex align-items-center gap-2 rounded-pill px-4 py-2">
            <a class="nav-link" href="map.php">
                <i class="bi bi-geo-alt"></i> Voir la carte
            </a>
        </button>
        <?php if ($isAdmin): ?>
        <button class="btn btn-outline-secondary custom-action-btn text-white d-flex align-items-center gap-2 rounded-pill px-4 py-2">
            <a class="nav-link" href="details.php">
                <i class="bi bi-plus-lg"></i> Ajouter un point de recharge
            </a>
        </button>
        <?php endif; ?>
    </section>

</main>

<?php include 'helpers/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>