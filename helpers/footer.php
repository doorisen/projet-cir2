

<!-- Footer -->
<footer class="custom-footer mt-auto py-3 px-4 d-flex justify-content-between align-items-center border-top border-secondary border-opacity-25 text-secondary small">
    <div>Projet CIR2 — ISEN Ouest 2026</div>
    <div>
        <?php if ($isAdmin): ?>
            <a href="helpers/cookie.php?action=logout" class="btn btn-sm btn-outline-secondary text-secondary custom-admin-btn">Mode client</a>
        <?php else: ?>
            <a href="helpers/cookie.php?action=login" class="btn btn-sm btn-outline-secondary text-secondary custom-admin-btn">Mode admin</a>
        <?php endif; ?>
    </div>
    <div>Valentin TANG-PATUREL - CIR2</div>
</footer>

