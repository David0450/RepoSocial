<?php ob_start(); ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar_categories">
        <div>
            <h1>Categorias</h1>
            <div class="categories_list" id="categories_list">
                
            </div>
        </div>
    </div>
    <div class="sidebar_tags">
        <div>
            <h1>Etiquetas</h1>
            <div class="tags_list" id="tags_list">

            </div>
        </div>
    </div>
    <script src="../Public/scripts/SidebarScript.js"></script>
</aside>
<?php $sidebar = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>
