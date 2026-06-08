<?php

require_once 'config/try_populate.php';

?>


<!-- Recherche -->
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recherche - IRVE Bretagne <?= $isAdmin ? '- Administration' : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<script src="assets/js/helpers.js" defer></script>
<script src="assets/js/filters.js" defer></script>
<script src="assets/js/search.js" defer></script>
<body class="d-flex flex-column min-vh-100 custom-bg <?= $isAdmin ? 'admin-mode' : '' ?>">

<?php include 'helpers/header.php'; ?>

<main class="container my-5 flex-grow-1 d-flex flex-column gap-4">
    
    <!-- Filtres -->
    <section class="p-4 rounded-3 custom-card text-light border border-secondary border-opacity-25 shadow-sm">
        <form id="search-form" class="row g-3 align-items-end">
            <!-- Filtrage par aménageur -->
            <div class="col-md-3">
                <label class="form-label text-secondary small text-uppercase fw-semibold">Aménageur</label>
                <select id="amenageur" class="form-select bg-dark text-light border-secondary border-opacity-25">
                    <option value="">Tous</option>
                </select>
            </div>
            <!-- Filtrage par type de prise -->
            <div class="col-md-3">
                <label class="form-label text-secondary small text-uppercase fw-semibold">Type de prise</label>
                <select id="prise" class="form-select bg-dark text-light border-secondary border-opacity-25">
                    <option value="">Tous</option>
                </select>
            </div>
            <!-- Filtrage par département -->
            <div class="col-md-3">
                <label class="form-label text-secondary small text-uppercase fw-semibold">Département</label>
                <select id="departement" class="form-select bg-dark text-light border-secondary border-opacity-25">
                    <option value="">Tous</option>
                </select>
            </div>
            <!-- Filtrage par station -->
            <div class="col-md-3">
                <label class="form-label text-secondary small text-uppercase fw-semibold">Station</label>
                <select id="station" class="form-select bg-dark text-light border-secondary border-opacity-25">
                    <option value="">Tous</option>
                </select>
            </div>
            <!-- Boutons -->
            <div class="d-flex gap-2">
                <?php if ($isAdmin): ?>
                <button class="btn btn-dark btn-outline-secondary text-white w-100">
                    <a class="nav-link" href="details.php"> <i class="bi bi-plus-lg"></i> Ajouter un point de recharge </a>
                </button>
                <?php endif; ?>
                <button type="reset" class="btn btn-outline-secondary w-100">Réinitialiser</button>
                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
            </div>
        </form>
    </section>

    <!-- Résultats -->
    <section class="rounded-3 custom-card border border-secondary border-opacity-25 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-custom">
                <!-- En-tête -->
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Station</th>
                        <th class="py-3">Enseigne</th>
                        <th class="py-3">Prise</th>
                        <th class="py-3">Puissance</th>
                        <th class="py-3">Localisation</th>
                        <th class="pe-4 py-3 text-end"></th>
                    </tr>
                </thead>
                <!-- Points de recharge -->
                <tbody id="results-body"></tbody>
            </table>
        </div>
    </section>

</main>

<?php include 'helpers/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>