<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/head.php'; ?>
<?php ob_start(); ?>
<section class="account_section content_section">
    <div class="account_container">
        <div class="account_header">
            <h1><?= $_SESSION['user']['username'] ?></h1>
            <button id="editaccountButton">Editar Perfil</button>
        </div>
        <div class="account_info">
            <div class="account_picture">
                <img src="<?=$PATH?>Public/assets/images/account/default_account.png" alt="account Picture" id="accountPicture">
            </div>
            <div class="account_details">
                <h2 id="username"><?= $_SESSION['user']['username'] ?></h2>
                <p id="email">Correo Electrónico</p>
                <p id="bio">Biografía breve del usuario.</p>
            </div>
        </div>
        <div class="account_projects">
            <h2>Proyectos del Usuario</h2>
            <ul id="userProjectsList">
                <!-- Lista de proyectos del usuario -->
            </ul>
        </div>
    </div>
    <div class="modal" id="editaccountModal">
        <div class="modal_content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Editar Perfil</h2>
            <form id="editaccountForm">
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
    <script src="<?=$PATH?>Public/scripts/accountScript.js"></script>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
