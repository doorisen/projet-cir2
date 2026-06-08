<?php

require_once 'config/try_populate.php';
$id = $_GET['id'] ?? null;
$isCreation = ($isAdmin && empty($id));

?>


<!-- Détails -->
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isCreation ? 'Nouveau point' : 'Détails' ?> - IRVE Bretagne <?= $isAdmin ? '- Administration' : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="d-flex flex-column min-vh-100 custom-bg <?= $isAdmin ? 'admin-mode' : '' ?>">

<?php include 'helpers/header.php'; ?>

<main class="container my-5 flex-grow-1">
    
    <!-- Bouton Retour -->
    <div class="mb-4">
        <a href="search.php" class="text-decoration-none text-secondary d-inline-flex align-items-center gap-2 custom-action-btn px-3 py-1 rounded-pill">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- En-tête -->
    <div class="mb-4">
        <h2 id="station-name" class="fw-bold mb-1 text-white"><?= $isCreation ? 'Nouveau point de recharge' : '...' ?></h2>
        <p id="station-id" class="text-secondary mb-0 font-monospace fs-6"><?= $isCreation ? 'Veuillez renseigner les informations ci-dessous' : '...' ?></p>
    </div>

    <!-- Conteneur global sur 2 colonnes -->
    <section class="custom-card p-4 rounded-3 shadow-sm border border-secondary border-opacity-25 w-100">
        <div class="row g-3 row-cols-1 row-cols-md-2">

        <?php if ($isAdmin): ?>
            <!-- Station -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <label for="station-select" class="text-secondary small text-uppercase fw-semibold d-block mb-2">Station</label>
                    <select id="station-select" class="form-select bg-dark text-light border-secondary shadow-none">
                        <option value="">Sélectionnez une station...</option>
                    </select>
                </div>
            </div>
        <?php else: ?>
            <!-- Adresse -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-1">Adresse</span>
                    <span id="adresse" class="fs-5 text-light">...</span>
                </div>
            </div>
        <?php endif; ?>
            <!-- Aménageur -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <label for="amenageur-select" class="text-secondary small text-uppercase fw-semibold d-block mb-2">Aménageur</label>
                    <?php if ($isAdmin): ?>
                        <select id="amenageur-select" class="form-select bg-dark text-light border-secondary shadow-none">
                            <option value="">Sélectionnez un aménageur...</option>
                        </select>
                    <?php else: ?>
                        <span id="amenageur" class="fs-5 text-light">...</span>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Opérateur -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <label for="operateur-select" class="text-secondary small text-uppercase fw-semibold d-block mb-2">Opérateur</label>
                    <?php if ($isAdmin): ?>
                        <select id="operateur-select" class="form-select bg-dark text-light border-secondary shadow-none">
                            <option value="">Sélectionnez un opérateur...</option>
                        </select>
                    <?php else: ?>
                        <span id="operateur" class="fs-5 text-light">...</span>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Puissance nominale -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-1">Puissance nominale</span>
                    <?php if ($isAdmin): ?>
                        <div class="input-group">
                            <input type="number" step="0.1" id="puissance-input" class="form-control bg-dark text-light border-secondary shadow-none">
                            <span class="input-group-text bg-dark text-secondary border-secondary">kW</span>
                        </div>
                    <?php else: ?>
                        <span id="puissance" class="fs-5 text-light">...</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php if (!$isAdmin): ?>
            <!-- Horaires -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-1">Horaires</span>
                    <span id="horaires" class="fs-5 text-light">...</span>
                </div>
            </div>
        <?php endif; ?>
            <!-- Cable t2 -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-1">Cable t2 attaché</span>
                    <?php if ($isAdmin): ?>
                        <select id="cable-t2-input" class="form-select bg-dark text-light border-secondary shadow-none">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    <?php else: ?>
                        <span id="cable-t2" class="fs-5 text-light">...</span>
                    <?php endif; ?>
                </div>
            </div>
            <!--Tarification -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-1">Tarification</span>
                    <?php if ($isAdmin): ?>
                        <input type="text" id="tarification-input" class="form-control bg-dark text-light border-secondary shadow-none" placeholder="Ex: 0.40€/kWh">
                    <?php else: ?>
                        <span id="tarification" class="fs-5 text-light">...</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php if (!$isAdmin): ?>
            <!-- Raccordement -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-1">Raccordement</span>
                    <span id="raccordement" class="fs-5 text-light">...</span>
                </div>
            </div>
        <?php endif; ?>
            <!-- Types de prise disponibles -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-3">Types de prise disponibles</span>
                    <?php if ($isAdmin): ?>
                        <select id="add-prise" class="form-select bg-dark text-light border-secondary shadow-none mb-3">
                            <option value="">+ Ajouter une prise...</option>
                            <option value="EF">EF</option>
                            <option value="Type 2">Type 2</option>
                            <option value="Combo CCS">Combo CCS</option>
                            <option value="CHAdeMO">CHAdeMO</option>
                            <option value="Autre">Autre</option>
                        </select>
                    <?php endif; ?>
                    <div id="prises" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
            <!-- Paiement -->
            <div class="col">
                <div class="bg-dark p-3 rounded-2 h-100">
                    <span class="text-secondary small text-uppercase fw-semibold d-block mb-3">Paiement</span>
                    <?php if ($isAdmin): ?>
                        <select id="add-paiement" class="form-select bg-dark text-light border-secondary shadow-none mb-3">
                            <option value="">+ Ajouter un paiement...</option>
                            <option value="Gratuit">Gratuit</option>
                            <option value="CB">CB</option>
                            <option value="Acte">Acte</option>
                            <option value="Autre">Autre</option>
                        </select>
                    <?php endif; ?>
                    <div id="paiements" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>

        </div>
        <!-- Barre d'action -->
        <?php if ($isAdmin): ?>
            <div class="d-flex justify-content-between items-center mt-4 pt-3 border-top border-secondary border-opacity-25 flex-wrap gap-2">
            <?php if (!$isCreation): ?>
                <button id="btn-delete" class="btn btn-outline-danger d-flex align-items-center gap-2 rounded-pill px-3">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            <?php endif; ?>
                <div class="d-flex gap-2">
                    <button id="btn-reset" class="btn btn-outline-secondary rounded-pill px-3" disabled>Réinitialiser</button>
                    <button id="btn-save" class="btn btn-primary rounded-pill px-4" disabled>Valider</button>
                </div>
            </div>
        <?php endif; ?>
    </section>

</main>

<?php include 'helpers/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/helpers.js"></script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/details.js"></script>
</body>
</html>