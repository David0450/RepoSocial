<?php
include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/head.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php ob_start(); ?>
<section class="profile_section content_section">
    <div class="profile_info">
        <div>
            <div class="profile_avatar">
                <img src="<?= $PATH ?><?= $user['avatar_url'] ?>" alt="Avatar del perfil">
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
                <span></span>
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
        <?php if ($isLoggedIn && $_SESSION['user']['username'] != $_GET['parametro']): ?>
        <div class="profile_actions">
            <button class="btn follow_btn" id="followButton">Seguir</button>
            <a href="<?= $PATH ?>chats/new/@<?= $_GET['parametro'] ?>">
                <button class="btn message_btn">Mensaje</button>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="profile_projects">
        <h1>Proyectos subidos</h1>
        <div class="projects_list" id="projects_list">

        </div>
    </div>
</section>
<script>
    const USERNAME = '<?= $_GET['parametro'] ?>';
    const PROFILE_USERNAME = '<?= $_GET['parametro'] ?>';
    const USER_ID = '<?php if($isLoggedIn) {echo intval($_SESSION['user']['id']);} else {echo '';} ?>';
    const BASE_PATH = '<?=$PATH?>';
</script>
<script src="<?=$PATH?>Public/scripts/ProfileScript.js"></script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>