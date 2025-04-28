<?php ob_start(); ?>
<header>
	<div>
		<a class="logo-container" id="headerLogoContainer" href="?uri=home">
			<object data="../Public/assets/images/logo/logo.svg">
				<img src="../Public/assets/images/logo/logo.png" alt="logo">
			</object>
			<h1 class="logo-title">TECHIE</h1>
		</a>
		<div>
			<div class="main-header">
				<div class="searchbar">
					<div>
						<small>Escribe aquí para buscar...</small>
						<input type="text" name="asd" id="">
					</div>
					<div class="search-icon">
						<object data="../Public/assets/icons/SearchIcon.svg">
							<img src="../Public/assets/icons/SearchIcon.png" alt="search_icon">
						</object>
					</div>
				</div>
				<div class="right-info">
					<?php if (isset($_SESSION['user_id'])): ?>
					<div class="chat-icon icon">
						<i class="fa-solid fa-comment-dots"></i>
					</div>
					<div class="notifications-icon icon">
						<i class="fa-solid fa-bell"></i>
					</div>
					<div class="header-profile">
						<div>
							<div class="inside-box">									
								<object data="../Public/assets/icons/User_light.svg">
									<img src="../Public/assets/icons/User_light.png" alt="user_icon">
								</object>
							</div>
						</div>
						<div class="profile-name">
							<a href="?uri=user/profile"><?= $_SESSION['username'] ?></a>
							<i id="dropdownIcon" class="fa-solid fa-caret-down"></i>
						</div>
					</div>
					<div id="dropdown">
						<ul>
							<li><a href="?uri=user/profile">Perfil</a></li>
							<li><a href="?uri=user/logout">Cerrar sesión</a></li>
						</ul>
					</div>
					<?php else: ?>
					<button class="login-button_header" id="loginButton">Iniciar sesión</button>
					<button class="signup-button_header" id="signupButton">Crear cuenta</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<script src="../Public/scripts/HeaderScript.js"></script>
</header>
<?php $header = ob_get_clean(); ?>