<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>

<?php ob_start(); ?>
<section class="landing_section">
    <article class="hero fade-up">
        <div class="logo-container" id="headerLogoContainer">
            <object data="<?= $PATH ?>Public/assets/images/logo/Logo.svg">
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
                <!--<?php if (!$isLoggedIn): ?>
                    <a href="<?= $PATH ?>signup" class="cta">Crear cuenta</a>
                    <a href="<?= $PATH ?>login" class="cta ghost">Iniciar sesión</a>
                <?php endif; ?>-->
                <a href="<?= $PATH ?>hub" class="cta ghost">Ver proyectos</a>
            </div>
        </div>
    </article>

    <article class="feature-section">
        <div class="feature-content">
            <div class="text">
                <h2>Chatea en tiempo real</h2>
                <p>Conversa con otros miembros de la comunidad.</p>
                <ul class="feature-bullets">
                    <li>Mensajes instantáneos</li>
                    <li>Notificaciones al momento</li>
                    <li>Chats uno a uno privados</li>
                </ul>
            </div>
            <div class="image">
                <img src="https://cdn-icons-png.flaticon.com/512/2462/2462719.png" alt="Chat en tiempo real">
            </div>
        </div>
    </article>

    <article class="feature-section">
        <div class="feature-content">
            <div class="text">
                <h2>Publica proyectos</h2>
                <p>Muestra en lo que estás trabajando y encuentra colaboradores.</p>
                <ul class="feature-bullets">
                    <li>Comparte tus repos públicos</li>
                    <li>Agrega descripción y tecnologías</li>
                    <li>Recibe feedback y likes</li>
                </ul>
            </div>
            <div class="image">
                <img src="https://cdn-icons-png.flaticon.com/512/1534/1534977.png" alt="Publicar proyectos">
            </div>
        </div>
    </article>

    <article class="feature-section">
        <div class="feature-content">
            <div class="text">
                <h2>Conéctate con expertos</h2>
                <p>Sigue a otros usuarios y mantente al día con sus publicaciones.</p>
                <ul class="feature-bullets">
                    <li>Explora perfiles técnicos</li>
                    <li>Sigue usuarios destacados</li>
                    <li>Descubre nuevos talentos</li>
                </ul>
            </div>
            <div class="image">
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Conectar con expertos">
            </div>
        </div>
    </article>

    <article class="join fade-up">
        <h2>Únete ahora</h2>
        <p>Empieza a construir tu comunidad tecnológica hoy mismo.</p>
        <a href="<?= $PATH ?>hub" class="cta">Ver proyectos</a>
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