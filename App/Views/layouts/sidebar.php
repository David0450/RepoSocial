
<?php ob_start(); ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar_categories">
        <div>
            <div class="sidebar_categories_header">
                <h1>Categorias</h1>
                <?php if (Security::isAdmin()): ?>
                <button class="add_category_button" id="addCategoryButton">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <?php endif; ?>
            </div>
            <div class="categories_list" id="categories_list">
                
            </div>
        </div>
    </div>
    <div class="sidebar_tags">
        <div>
            <div class="sidebar_tags_header">
                <h1>Etiquetas</h1>
                <?php if (Security::isAdmin()): ?>
                <button class="add_tag_button" id="addTagButton">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <?php endif; ?>
            </div>
            <div class="tags_list" id="tags_list">

            </div>
        </div>
    </div>
    <script src="<?=Config::PATH?>/Public/scripts/SidebarScript.js"></script>
</aside>
<?php $sidebar = ob_get_clean(); ?>
