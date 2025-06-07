<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>

<?php ob_start(); ?>
<section class="landing_section">
    <article class="hero" data-lenis-scroll>
        <div class="logo-container" id="headerLogoContainer">
            <object data="<?= $PATH ?>Public/assets/images/logo/logo.svg">
                <img src="<?= $PATH ?>Public/assets/images/logo/logo.png" alt="logo">
            </object>
            <div>
                <h1 class="logo-title">RepoSocial</h1>
            </div>
        </div>
        <div class="hero-text">
            <h2>La red social para apasionados por la tecnología</h2>
            <p>Conecta, colabora y comparte ideas con personas como tú.</p>
            <div class="hero-buttons">
                <?php if (!$isLoggedIn): ?>
                    <a href="<?= $PATH ?>signup" class="cta">Crear cuenta</a>
                    <a href="<?= $PATH ?>login" class="cta ghost">Iniciar sesión</a>
                <?php endif; ?>
                <a href="<?= $PATH ?>hub" class="cta ghost">Ver proyectos</a>
            </div>
        </div>
    </article>

    <article class="feature-section">
        <div class="feature-content">
            <div class="text">
                <h2>Chatea en tiempo real</h2>
                <p>Conversa con otros miembros de la comunidad.</p>
            </div>
            <div class="image"></div>
        </div>
    </article>

    <article class="feature-section">
        <div class="feature-content">
            <div class="text">
                <h2>Publica proyectos</h2>
                <p>Muestra en lo que estás trabajando y encuentra colaboradores.</p>
            </div>
            <div class="image"></div>
        </div>
    </article>

    <article class="feature-section">
        <div class="feature-content">
            <div class="text">
                <h2>Conéctate con expertos</h2>
                <p>Sigue a otros usuarios y mantente al día con sus publicaciones.</p>
            </div>
            <div class="image"></div>
        </div>
    </article>

    <article class="join" data-lenis-scroll>
        <h2>Únete ahora</h2>
        <p>Empieza a construir tu comunidad tecnológica hoy mismo.</p>
        <?php if (!$isLoggedIn): ?>
            <a href="<?= $PATH ?>signup" class="cta">Crear cuenta gratuita</a>
        <?php else: ?>
            <a href="<?= $PATH ?>hub" class="cta">Ver proyectos</a>
        <?php endif; ?>
    </article>

</section>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    gsap.registerPlugin(ScrollTrigger);
</script>
<script src="<?= $PATH ?>Public/scripts/LandingScript.js"></script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>