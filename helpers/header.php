

<!-- Header -->
<header class="navbar navbar-expand custom-header py-3 px-4 shadow-sm">
<div class="container-fluid px-0">
    <!-- Logo + Brand -->
    <a class="navbar-brand d-flex align-items-center gap-2 text-white fw-bold fs-4" href="index.php">
        <?php if ($isAdmin): ?>
            <i class="bi bi-gear"></i>
            IRVE Bretagne - Administration
        <?php else: ?>
            <i class="bi bi-ev-station"></i>
            IRVE Bretagne
        <?php endif; ?>
    </a>
    
    <!-- Boutons -->
    <ul class="navbar-nav ms-auto gap-2">
        <li class="nav-item">
            <a class="nav-link nav-btn-outline" href="index.php">
                <i class="bi bi-house-door"></i> Accueil
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-btn-outline" href="search.php">
                <i class="bi bi-search"></i> Recherche
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-btn-outline" href="map.php">
                <i class="bi bi-geo-alt"></i> Carte
            </a>
        </li>
    </ul>
</div>
</header>

