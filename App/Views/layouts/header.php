<?php ob_start(); ?>
<header>
	<div>
		<a class="logo-container" id="headerLogoContainer" href="<?= $PATH?>home">
			<object data="<?=$PATH?>Public/assets/images/logo/logo.svg">
				<img src="<?=$PATH?>Public/assets/images/logo/logo.png" alt="logo">
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
						<object data="<?=$PATH?>Public/assets/icons/SearchIcon.svg">
							<img src="<?=$PATH?>Public/assets/icons/SearchIcon.png" alt="search_icon">
						</object>
					</div>
				</div>
				<div class="right-info">
					<?php if ($isLoggedIn): ?>
					<a class="chat-icon icon" href="<?= $PATH?>chats">
						<i class="fa-solid fa-comment-dots"></i>
					</a>
					<div class="notifications-icon icon">
						<i class="fa-solid fa-bell"></i>
					</div>
					<div class="header-profile">
						<div>
							<div class="inside-box">									
								<object data="<?=$PATH?><?= $_SESSION['user']['avatar_url'] ?>">
									<img src="<?=$PATH?>Public/assets/icons/User_light.png" alt="user_icon">
								</object>
							</div>
						</div>
						<div class="profile-name">
							<a href="<?= $PATH?>@<?= $_SESSION['user']['username'] ?>/profile"><?= $_SESSION['user']['username'] ?></a>
							<i id="dropdownIcon" class="fa-solid fa-caret-down"></i>
						</div>
					</div>
					<div id="dropdown">
						<ul>
							<li><a href="<?= $PATH?>@<?= $_SESSION['user']['username'] ?>/profile">Mi perfil</a></li>
							<li><a href="<?= $PATH?>user/account">Mi cuenta</a></li>
							<li><a href="<?=$PATH?>user/logout">Cerrar sesión</a></li>
						</ul>
					</div>
					<?php else: ?>
					<a href="<?= $PATH?>login">
						<button class="login-button_header">
							<i class="fa-solid fa-right-to-bracket"></i>
							<span>Inicia sesión</span>
						</button>
					</a>
					<a href="https://github.com/login/oauth/authorize?client_id=Ov23lijzgiVY3WIWZerl&scope=repo user:email">					
						<button class="social-btn" id="githubLoginButton">
                    		<i class="fa-brands fa-github"></i>
                    		<span>Únete con GitHub</span>
                		</button>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<script src="<?=$PATH?>Public/scripts/HeaderScript.js"></script>
</header>
<?php $header = ob_get_clean(); ?>