<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/head.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php ob_start(); ?>
<section class="profile_section content_section">
    <div class="profile_info">
        <div>
            <div class="profile_avatar">
                <img src="<?= Config::PATH ?><?= $user['avatar_url'] ?>" alt="Avatar del perfil">
            </div>
        </div>
        <h1 class="profile_username">
            <?= $_GET['parametro'] ?>
        </h1>
        <h3 class="profile_bio" id="userBio">
        </h3>
        <div class="profile_counts">
            <h5 class="posts_count" id="postsCount">
                <span>Publicaciones</span>
                <span>Error</span>
            </h5>
            <h5 class="followers_count" id="followersCount">
                <span>Seguidores</span>
                <span></span>
            </h5>
            <h5 class="following_count" id="followingCount">
                <span>Siguiendo</span>
                <span></span>
            </h5>
        </div>
    </div>
    <div class="profile_projects">
        <h1>Proyectos subidos</h1>
        <div class="projects_list" id="projects_list">

        </div>
    </div>
</section>
<script>
    const USERNAME = '<?= $_GET['parametro'] ?>';
    const BASE_PATH = '<?=Config::PATH?>';
</script>
<script src="<?=Config::PATH?>Public/scripts/ProfileScript.js"></script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>