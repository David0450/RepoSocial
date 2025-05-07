<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/head.php'; ?>
<?php ob_start(); ?>
<section class="profile_section content_section">
    <div class="profile_container">
        <div class="profile_header">
            <h1><?= $_SESSION['user']['username'] ?></h1>
            <button id="editProfileButton">Editar Perfil</button>
        </div>
        <div class="profile_info">
            <div class="profile_picture">
                <img src="<?=Config::PATH?>Public/assets/images/profile/default_profile.png" alt="Profile Picture" id="profilePicture">
            </div>
            <div class="profile_details">
                <h2 id="username"><?= $_SESSION['user']['username'] ?></h2>
                <p id="email">Correo Electrónico</p>
                <p id="bio">Biografía breve del usuario.</p>
            </div>
        </div>
        <div class="profile_projects">
            <h2>Proyectos del Usuario</h2>
            <ul id="userProjectsList">
                <!-- Lista de proyectos del usuario -->
            </ul>
        </div>
    </div>
    <div class="modal" id="editProfileModal">
        <div class="modal_content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Editar Perfil</h2>
            <form id="editProfileForm">
                <div class="input-container">
                    <label for="newUsername">Nuevo Nombre de Usuario</label>
                    <input type="text" id="newUsername" name="newUsername" required>
                </div>
                <div class="input-container">
                    <label for="newEmail">Nuevo Correo Electrónico</label>
                    <input type="email" id="newEmail" name="newEmail" required>
                </div>
                <div class="input-container">
                    <label for="newBio">Nueva Biografía</label>
                    <textarea id="newBio" name="newBio"></textarea>
                </div>
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>
    <script src="<?=Config::PATH?>Public/scripts/ProfileScript.js"></script>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
