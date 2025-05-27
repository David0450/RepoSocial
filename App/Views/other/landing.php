<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>

<?php ob_start(); ?>
<section class="landing_section">
    <article class="hero">
        <a class="logo-container" id="headerLogoContainer" href="<?= $PATH?>home">
            <object data="<?=$PATH?>Public/assets/images/logo/logo.svg">
                <img src="<?=$PATH?>Public/assets/images/logo/logo.png" alt="logo">
            </object>
            <div>
                <h1 class="logo-title">RepoSocial</h1>
            </div>
        </a>
        <div class="hero-text">
            <h2>La red social para apasionados por la tecnología</h2>
            <p>Conecta, colabora y comparte ideas con personas como tú.</p>
            <div class="hero-buttons">
                <?php if (!$isLoggedIn): ?>
                <a href="<?= $PATH ?>signup" class="cta">Crear cuenta</a>
                <a href="<?= $PATH ?>login" class="cta ghost">Iniciar sesión</a>
                <?php endif; ?>
                <a href="<?= $PATH ?>projects/view" class="cta ghost">Ver proyectos</a>
            </div>
        </div>
    </article>

    <article id="features" class="features">
        <h2>¿Qué puedes hacer?</h2>
        <div class="cards">
            <div class="card">
                <h3>Chatea en tiempo real</h3>
                <p>Conversa con otros miembros de la comunidad.</p>
            </div>
            <div class="card">
                <h3>Publica proyectos</h3>
                <p>Muestra en lo que estás trabajando y encuentra colaboradores.</p>
            </div>
            <div class="card">
                <h3>Conéctate con otros</h3>
                <p>Sigue a otros usuarios y mantente al día con sus publicaciones.</p>
            </div>
        </div>
    </article>

    <article id="join" class="join">
        <h2>Únete ahora</h2>
        <p>Empieza a construir tu comunidad tecnológica hoy mismo.</p>
        <?php if (!$isLoggedIn): ?>
        <a href="signup.php" class="cta">Crear cuenta gratuita</a>
        <?php else: ?>
        <a href="<?= $PATH ?>projects/view" class="cta">Ver proyectos</a>
        <?php endif; ?>
    </article>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>