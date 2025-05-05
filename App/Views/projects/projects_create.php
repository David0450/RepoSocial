<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
?>
<?php ob_start(); ?>
<section style="width: 100%">
    <div class="project_creation_section">
        <div class="project_creation_container">
            <h1>Mis Proyectos</h1>
            <!--<form id="createProjectForm" method="POST" action="?uri=projects/create">
                <div class="input-container">
                    <label for="title">Título del Proyecto</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="input-container">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="input-container">
                    <label for="category">Categoría</label>
                    <select id="categorySelect" name="category" required >
                        <option value="" disabled selected>Selecciona una categoría</option>
                    </select>
                </div>
                <button type="submit">Crear Proyecto</button>
            </form>-->
            <div class="projects_list" id="projects_list">
            </div>
        </div>
    </div>
    <script src="<?=Config::PATH?>Public/scripts/ProjectCreationScript.js"></script>
</section>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>