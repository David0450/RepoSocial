<?php ob_start(); ?>
<header>
	<div>
		<div class="logo-container">
			<object data="../Public/assets/images/logo/logo.svg">
				<img src="../Public/assets/images/logo/logo.png" alt="logo">
			</object>
			<h1 class="logo-title">TECHIE</h1>
		</div>
		<div>
			<div class="header-icons">
				<div class="header-icons_home">
					<img src="../Public/assets/icons/home.svg" alt="">
				</div>
				<div class="header-icons_forums">
					<img src="../Public/assets/icons/forums.svg" alt="">
				</div>
				<div class="header-icons_events">
					<img src="../Public/assets/icons/events.svg" alt="">
				</div>
				<div class="header-icons_jobs">
					<img src="../Public/assets/icons/jobs.svg" alt="">
				</div>
				<div class="header-icons_projects">
					<img src="../Public/assets/icons/projects.svg" alt="">
				</div>
			</div>
			<div>
				<div class="searchbar">

				</div>
				<div>
					<div class="chat-icon"></div>
					<div class="notifications-icon"></div>
					<div class="header-profile"></div>
				</div>
			</div>
		</div>
	</div>
</header>
<?php $header = ob_get_clean(); ?>