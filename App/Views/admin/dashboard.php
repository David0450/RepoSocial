<?php
include __DIR__ . '/../layouts/head.php';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/footer.php';
?>
<?php ob_start(); ?>
<section class="admin_section">
    <article class="admin_overview">
        <div class="admin_card">
            <h2>Usuarios registrados</h2>
            <p id="userCount">--</p>
        </div>
        <div class="admin_card">
            <h2>Proyectos publicados</h2>
            <p id="projectCount">--</p>
        </div>
        <div class="admin_card">
            <h2>Reportes pendientes</h2>
            <p id="reportCount">--</p>
        </div>
    </article>

    <article class="admin_selection">
        <div class="admin_view_tabs">
            <button id="dashboardUsers" class="admin_tab active">Usuarios</button>
            <button id="dashboardProjects" class="admin_tab">Proyectos</button>
            <button id="dashboardCategories" class="admin_tab">Categorías</button>
            <button id="dashboardTags" class="admin_tab">Etiquetas</button>
        </div>
    </article>

    <article class="admin_content" id="dashboardTable">
    </article>
</section>
<script>
</script>
<script src="<?=$PATH?>Public/scripts/DashboardScript.js"></script>
<?php 
$content = ob_get_clean();
include __DIR__ . '/../mainView.php';
?>