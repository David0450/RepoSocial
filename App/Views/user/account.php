<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/head.php'; ?>
<?php ob_start(); ?>
<section class="edit_account_section">
  <h1>Editar Mi Cuenta</h1>

  <form class="edit_account_form">
    <div class="form_group">
      <label for="username">Nombre de usuario</label>
      <input type="text" id="username" name="username" placeholder="Nuevo nombre de usuario" />
    </div>

    <div class="form_group">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" />
    </div>

    <div class="form_group">
      <label for="bio">Biografía</label>
      <textarea id="bio" name="bio" rows="4" placeholder="Cuéntanos algo sobre ti..."></textarea>
    </div>

    <div class="form_group">
      <label for="profile_picture">Foto de perfil</label>
      <input type="file" id="profile_picture" name="profile_picture" accept="image/*" />
    </div>

    <div class="form_actions">
      <button type="submit">Guardar Cambios</button>
    </div>
  </form>
</section>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
