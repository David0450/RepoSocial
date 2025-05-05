<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<article class="projects_list_article">
    <?php if (Security::isLoggedIn()): ?>
    <section class="project_creation_section">
        <div class="project_creation_container">
            <h1>Crear Proyecto</h1>
            <a href="<?= Config::PATH ?>projects/create">
                <button class="create_project_button" id="my_projects_button">Mis proyectos</button>
            </a>
        </div>
    </section>
    <?php endif; ?>
    <section class="projects_section">
        <div class="projects_container">
            <h1>Proyectos</h1>
            <div class="projects_list" id="projects_list">
                <!-- Lista de proyectos -->
                <!--
                <?php foreach ($projects as $project): ?>
                    <div class="project_card">
                        <h2><?= htmlspecialchars($project['title']) ?></h2>
                        <p><?= htmlspecialchars($project['description']) ?></p>
                        <a href="?uri=projects/show/<?= $project['id'] ?>">Ver Proyecto</a>
                    </div>
                <?php endforeach; ?>
                -->
            </div>
        </div>
        <?php 
        ?>
    </section>
    <!--<section class="project_search_section">
        <div class="project_search_container">
            <h1>Buscar Proyecto</h1>
            <input type="text" id="searchInput" placeholder="Buscar por título...">
            <button id="searchButton">Buscar</button>
            <div id="searchResults"></div>
        </div>
    </section>-->
    <script src="<?=Config::PATH?>Public/scripts/ProjectScript.js"></script>
</article>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
