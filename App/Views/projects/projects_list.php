<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<article class="projects_list_section content_section">
    <?php if (Security::isLoggedIn()): ?>
    <section class="project_creation_section">
        <div class="project_creation_container">
            <!--<h1>Crear Proyecto</h1>
            <a href="<?= Config::PATH ?>user/projects">
                <button class="create_project_button">Mis proyectos</button>
            </a>-->
            <div>
                <div>
                    <div class="inside-box text-bg-dark">									
                        <object data="<?=Config::PATH?>Public/assets/images/logo/logo.svg">
                            <img src="<?=Config::PATH?>Public/assets/images/logo/logo.png" alt="logo">
                        </object>
                    </div>
                </div>
                <div class="post_text_container">
                    <div></div>
                    <div class="post_text">
                        <span>¿Tienes algún proyecto en GitHub que quieras que el mundo vea?</span>
                    </div>
                </div>
            </div>
            <div class="upload_projects_container">
                <span>
                    Sube tus proyectos
                </span>
                <a href="<?= Config::PATH ?>users/@<?= $_SESSION['user']['username'] ?>/github-repos/view">
                    <button class="create_project_button">Mis proyectos</button>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <section class="projects_section">
        <div class="projects_container">
            <h1>Tu Feed</h1>
            <div id="loading" class="fw-bold ta-center h5">Cargando...</div>
            <div class="projects_list" id="projects_list">
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
    <script>
        const BASE_PATH = '<?= Config::PATH ?>';
    </script>
    <script src="<?=Config::PATH?>Public/scripts/HomeScript.js"></script>
</article>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
