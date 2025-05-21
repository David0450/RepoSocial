<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<section style="width: 100%" class="content_section">
    <div class="project_creation_section">
        <div class="project_upload_container">
            <h1>Mis Proyectos</h1>
            <div class="projects_list" id="projects_list">

            </div>
            <!--<div id="pagination">
                <button id="prevPage" class="pagination_button">Anterior</button>
                <span id="pageInfo"></span>
                <button id="nextPage" class="pagination_button">Siguiente</button>
            </div>-->
        </div>
    </div>
    <script>
    const BASE_PATH = "<?= Config::PATH ?>";
    const USERNAME = "<?= $_SESSION['user']['username'] ?>";
    </script>
    <script src="<?=Config::PATH?>Public/scripts/ProjectCreationScript.js"></script>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>