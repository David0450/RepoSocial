$(function() {

    let sidebar = document.querySelector('.sidebar');

    $.ajax({
        url: '?uri=categories',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Populate the sidebar with categories
            var categoriesList = $('#categories_list');
            categoriesList.empty(); // Clear existing items

            $.each(data, function(index, category) {
                var item = `
                    <div class="category_item" value="${category.id}">
                        <div class="category_icon">
                            <i class="${category.icon}"></i>
                        </div>
                        <div class="category_name">
                            <h2>${category.title}</h2>
                            <small>${category.projects} posts en esta categoría</small>
                        </div>
                    </div>`;
                categoriesList.append(item);

            });
        },
        error: function(xhr, status, error) {
            var categoriesList = $('#categories_list');
            categoriesList.empty();
            categoriesList.append('<p class="sidebar_error">No se han podido obtener categorías</p>');
            $(".sidebar_error").css("font-size", "1.2rem");
            $(".sidebar_error").css("color", "var(--ghost-white)");
            $(".sidebar_error").css("text-align", "center");
            $(".sidebar_error").css("font-weight", "bold");
            $(".sidebar_error").width("100%");
        }
    })

    $.ajax({
        url: '?uri=tags',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.length == 0) {
                var tagsList = $('#tags_list');
                $("#tags_list").css("display", "flex");
                tagsList.empty();
                tagsList.append('<p class="sidebar_error">No se han podido obtener etiquetas</p>');
                $(".sidebar_error").css("font-size", "1.2rem");
                $(".sidebar_error").css("color", "var(--ghost-white)");
                $(".sidebar_error").css("text-align", "center");
                $(".sidebar_error").css("font-weight", "bold");
                $(".sidebar_error").width("100%");
            } else {
                var tagsList = $('#tags_list');
                tagsList.empty(); // Clear existing items
                
                $.each(data, function(index, tag) {
                    var item = `
                    <div class="tag_item" value="${tag.id}">
                        <span>`+tag.title+`</span>
                    </div>
                    `
                    tagsList.append(item);
                });
            }
        },
        error: function(xhr, status, error) {
            var tagsList = $('#tags_list');
            $("#tags_list").css("display", "flex");
            tagsList.empty();
            tagsList.append('<p class="sidebar_error">No se han podido obtener etiquetas</p>');
            $(".sidebar_error").css("font-size", "1.2rem");
            $(".sidebar_error").css("color", "var(--ghost-white)");
            $(".sidebar_error").css("text-align", "center");
            $(".sidebar_error").css("font-weight", "bold");
            $(".sidebar_error").width("100%");
        }
    })
});
