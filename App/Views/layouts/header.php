<?php ob_start(); ?>
<header>
	<div>
		<a class="logo-container" id="headerLogoContainer" href="<?= $PATH?>hub">
			<object data="<?=$PATH?>Public/assets/images/logo/logo.svg">
				<img src="<?=$PATH?>Public/assets/images/logo/logo.png" alt="logo">
			</object>
			<div>
				<h1 class="logo-title">RepoSocial</h1>
				<small>Administrador</small>
			</div>
		</a>
		<div>
			<div class="main-header">
				<div class="left-info">
					<div class="searchbar" id="searchbar">
						<div>
							<small id="searchPlaceholder">Escribe aquí para buscar...</small>
							<input type="text" name="q" id="search" autocomplete="off">
						</div>
						<div class="search-icon">
							<object data="<?=$PATH?>Public/assets/icons/SearchIcon.svg">
								<img src="<?=$PATH?>Public/assets/icons/SearchIcon.png" alt="search_icon">
							</object>
						</div>
						<div class="search-results" id="searchResults">
    						<div class="search-section" id="userResultsSection"></div>
    						<div class="search-section" id="projectResultsSection"></div>
							<span id="noResults"></span>
						</div>
					</div>
				</div>
				<div class="right-info">
					<?php if ($isLoggedIn): ?>
					<a class="chat-icon icon" href="<?= $PATH?>chats">
						<i class="fa-solid fa-message"></i>
					</a>
					<!-- Notificaciones para futuras versiones -->
					<!--
					<div class="notifications-icon icon">
						<i class="fa-solid fa-bell"></i>
					</div>
					-->
					<div class="header-profile">
						<div>
							<div class="inside-box">									
								<object data="<?=$PATH?><?= $_SESSION['user']['avatar_url'] ?>">
									<img src="<?=$PATH?>Public/assets/icons/User_light.png" alt="user_icon">
								</object>
							</div>
						</div>
						<div class="profile-name">
							<a href="<?= $PATH?>@<?= $_SESSION['user']['username'] ?>"><?= $_SESSION['user']['username'] ?></a>
							<i id="dropdownIcon" class="fa-solid fa-caret-down"></i>
						</div>
					</div>
					<div id="dropdown">
						<ul>
							<?php if ($isAdmin): ?>
							<li><a href="<?= $PATH?>admin"><i class="fa-solid fa-toolbox"></i> Admin</a></li>
							<?php endif; ?>
							<li><a href="<?= $PATH?>@<?= $_SESSION['user']['username'] ?>/profile"><i class="fa-solid fa-user"></i> Perfil</a></li>
							<li><a href="<?=$PATH?>user/logout"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
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
	<!-- Menú hamburguesa para móviles -->
	<div class="mobile-header-bar">
	    <button id="hamburgerToggle" class="hamburger-button">
	        <i class="fa-solid fa-bars"></i>
	    </button>
	</div>

	<nav id="mobileMenu" class="mobile-nav hidden">
		<a href="#" id="toggleDropdownSearch"><i class="fa-solid fa-magnifying-glass"></i> Buscar</a>
		<div id="dropdownSearchContainer" class="mobile-search-dropdown hidden">
		    <input type="text" id="mobileSearchInput" placeholder="Buscar...">
		</div>
		<div class="search-results" id="searchResultsMobile" style="display: none;">
    		<div class="search-section" id="userResultsMobile"></div>
    		<div class="search-section" id="projectResultsMobile"><h4>Projectos</h4><ul class="project-results"><a href="https://github.com/David0450/SI"><i class="fa-solid fa-diagram-project" aria-hidden="true"></i><span>SI</span></a></ul></div>
			<span id="noResultsMobile"></span>
		</div>
	    <?php if ($isLoggedIn): ?>
	        <a href="<?= $PATH ?>chats"><i class="fa-solid fa-message"></i> Chats</a>
	        <a href="<?= $PATH ?>@<?= $_SESSION['user']['username'] ?>"><i class="fa-solid fa-user"></i> Perfil</a>
	        <?php if ($isAdmin): ?>
	            <a href="<?= $PATH ?>admin"><i class="fa-solid fa-toolbox"></i> Admin</a>
	        <?php endif; ?>
	        <a href="<?= $PATH ?>user/logout"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
	    <?php else: ?>
	        <a href="<?= $PATH ?>login"><i class="fa-solid fa-right-to-bracket"></i> Inicia sesión</a>
	        <a href="https://github.com/login/oauth/authorize?client_id=Ov23lijzgiVY3WIWZerl&scope=repo user:email">
	            <i class="fa-brands fa-github"></i> Únete con GitHub
	        </a>
	    <?php endif; ?>
	</nav>
	<script>
		const PATH = '<?= htmlspecialchars($PATH) ?>';
	</script>
	<script src="<?=$PATH?>Public/scripts/HeaderScript.js"></script>
</header>
<?php $header = ob_get_clean(); ?>