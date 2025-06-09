<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<section class="login_section">
    <a class="logo-container" href="?uri=home">
	    <object data="../Public/assets/images/logo/logo.svg">
			<img src="../Public/assets/images/logo/logo.png" alt="logo">
	    </object>
		<h1 class="logo-title">TECHIE</h1>
	</a>
    <h1>Bienvenido a Techie</h1>
    <p>Forma parte de nuestra comunidad</p>
    <div class="login_form">
        <form action="" method="POST" id="loginForm">
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
            <button type="submit" name="uri" value="user/login">Iniciar sesión</button>
        </form>
        <p>¿No tienes cuenta? <a href="?uri=signup" id="signupLink">Crear cuenta</a></p>
        <p>¿Olvidaste tu contraseña? <a href="#" id="forgotPasswordLink">Recuperar contraseña</a></p>
    </div>
    <div class="social_login">
        <p>O inicia sesión con</p>
        <div class="social_buttons">
            <!--<button id="googleLoginButton">Google</button>-->
            <button class="social-btn">
                <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Logo">
                <span>Google</span>
            </button>
            <button class="social-btn">
                <i class="fa-brands fa-github"></i>
                <span>GitHub</span>
            </button>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2025 Techie. Todos los derechos reservados.</p>
        <p><a href="#">Política de privacidad</a> | <a href="#">Términos de servicio</a></p>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>