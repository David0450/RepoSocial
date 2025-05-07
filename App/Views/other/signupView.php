<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<section class="signup_section content_section">
    <a class="logo-container" href="<?=Config::PATH?>home">
	    <object data="<?=Config::PATH?>Public/assets/images/logo/logo.svg">
			<img src="<?=Config::PATH?>Public/assets/images/logo/logo.png" alt="logo">
	    </object>
		<h1 class="logo-title">TECHIE</h1>
	</a>
    <h1>Bienvenido a Techie</h1>
    <p>Forma parte de nuestra comunidad</p>
    <div class="login_form">
        <form action="" method="POST" id="loginForm">
            <div class="input-container">
                <div>
                    <label for="name">Nombre</label>
                    <input type="name" name="name" id="name" required>
                </div>
                <div>
                    <label for="last_name">Apellido</label>
                    <input type="last_name" name="last_name" id="last_name" required>
                </div>
            </div>
            <div class="input-container">
                <div>
                    <label for="username">Usuario</label>
                    <input type="username" name="username" id="username" required>
                </div>
            </div>
            <div class="input-container">
                <div>
                    <label for="email">Correo electrónico</label>
                    <input type="email" name="email" id="email" required>
                </div>
            </div>
            <div class="input-container">
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required>
                </div>
            </div>
            <button type="submit" name="uri" value="user/signup">Crear cuenta</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="<?=Config::PATH?>login" id="loginLink">Inciar sesión</a></p>
    </div>
    <div class="social_login">
        <p>O inicia sesión con</p>
        <div class="social_buttons">
            <a href="https://github.com/login/oauth/authorize?client_id=Ov23lijzgiVY3WIWZerl&scope=repo user:email&prompt=consent">
                <button class="social-btn" id="githubLoginButton">
                    <i class="fa-brands fa-github"></i>
                    <span>GitHub</span>
                </button>
            </a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2025 Techie. Todos los derechos reservados.</p>
        <p><a href="#">Política de privacidad</a> | <a href="#">Términos de servicio</a></p>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>