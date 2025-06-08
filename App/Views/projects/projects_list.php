<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<article class="projects_list_section content_section">
    <?php if ($isLoggedIn): ?>
    <section class="project_creation_section">
        <div class="project_creation_container">
            <!--<h1>Crear Proyecto</h1>
            <a href="<?= $PATH ?>user/projects">
                <button class="create_project_button">Mis proyectos</button>
            </a>-->
            <div>
                <div>
                    <div class="inside-box text-bg-dark">									
                        <object data="<?=$PATH?>Public/assets/images/logo/Logo.svg">
                            <img src="<?=$PATH?>Public/assets/images/logo/logo.png" alt="logo">
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
                <a href="<?= $PATH ?>users/@<?= $_SESSION['user']['username'] ?>/github-repos/view">
                    <button class="create_project_button">Mis repositorios</button>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <section class="projects_section">
        <div class="projects_container">
            <div class="projects_list" id="projects_list">
            </div>
            <div id="loading" class="fw-bold ta-center h5">Cargando...</div>
        </div>
        <?php 
        ?>
    </section>
    <script>
        const BASE_PATH = '<?= $PATH ?>';
    </script>
    <script src="<?=$PATH?>Public/scripts/HomeScript.js"></script>
</article>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
