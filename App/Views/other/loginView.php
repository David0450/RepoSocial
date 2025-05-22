<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<section class="login_section content_section">
    <a class="logo-container" href="<?=$PATH?>home">
	    <object data="<?=$PATH?>Public/assets/images/logo/logo.svg">
			<img src="<?=$PATH?>Public/assets/images/logo/logo.png" alt="logo">
	    </object>
		<h1 class="logo-title">TECHIE</h1>
	</a>
    <h1>Bienvenido a Techie</h1>
    <p>Forma parte de nuestra comunidad</p>
    <div class="login_form">
        <form action="<?= $PATH?>user/login" method="POST" id="loginForm">
            <div class="input-container">
                <div>
                    <label for="username">Usuario</label>
                    <input type="text" name="username" id="username" required>
                </div>
            </div>
            <div class="input-container">
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required>
                </div>
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>
        <div class="signup_link">
            <p>¿No tienes cuenta?</p>
            <a href="https://github.com/login/oauth/authorize?client_id=Ov23lijzgiVY3WIWZerl&scope=repo user:email">					
				<button class="social-btn" id="githubLoginButton">
            		<i class="fa-brands fa-github"></i>
            		<span>Únete con GitHub</span>
            	</button>
			</a>
</div>
        <p>¿Olvidaste tu contraseña? <a href="#" id="forgotPasswordLink">Recuperar contraseña</a></p>
    </div>
    <!--<div class="social_login">
        <p>O inicia sesión con</p>
        <div class="social_buttons">
            <a href="https://github.com/login/oauth/authorize?client_id=Ov23lijzgiVY3WIWZerl&scope=repo user:email">
                <button class="social-btn" id="githubLoginButton">
                    <i class="fa-brands fa-github"></i>
                    <span>GitHub</span>
                </button>
            </a>
        </div>
    </div>-->
    <div class="footer">
        <p>&copy; 2025 Techie. Todos los derechos reservados.</p>
        <p><a href="#">Política de privacidad</a> | <a href="#">Términos de servicio</a></p>
    </div>
</section>
<!--<script src="<?=$PATH?>Public/scripts/LoginScript.js"></script>-->
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>