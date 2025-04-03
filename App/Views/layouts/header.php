<?php ob_start(); ?>
<header>
<script src="https://kit.fontawesome.com/aaeebdae3e.js" crossorigin="anonymous"></script>	
	<div>
		<div class="logo-container">
			<object data="../Public/assets/images/logo/logo.svg">
				<img src="../Public/assets/images/logo/logo.png" alt="logo">
			</object>
			<h1 class="logo-title">TECHIE</h1>
		</div>
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
							<p>Usuario Prueba</p>
							<a>
								<i class="fa-solid fa-caret-down"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<?php $header = ob_get_clean(); ?>