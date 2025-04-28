$(function() {

    let sidebar = document.querySelector('.sidebar');
    let addCategoryButton = document.querySelector('#addCategoryButton');
    let addTagButton = document.querySelector('#addTagButton');

    addCategoryButton.addEventListener('click', function() {
        console.log("click en el botón de añadir categoría");
        $.ajax({
            url: '?uri=categories',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Swal.fire({
                    title: `Categorías`,
                    width: "50%",
                    theme: 'dark',
                    html: `
                    <table id="tablaPropiedades" class="w-100">
                        <tr class="text-white" style="background-color: var(--dodger-blue);">
                            <th class="border py-2">Icono</th>
                            <th class="border py-2">Título</th>
                            <th class="border py-2">Acciones</th>
                        </tr>
                        `+data.map(function(category) {
                            return `
                            <tr>
                                <td class="border"><i class="${category.icon}"></i></td>
                                <td class="border fw-bold">${category.title}</td>
                                <td class="border" style="width: fit-content;">
                                    <button class="btn btn-danger deleteProperty" data-id="">Eliminar</button>
                                </td>
                            </tr>
                            `;
                        }).join('')+`
                    </table>
                    `,
                    icon: "info",
                    confirmButtonText: 'Añadir categoría',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar la lógica para añadir una nueva categoría
                        Swal.fire({
                            title: 'Añadir nueva categoría',
                            theme: 'dark',
                            html: `
                                <input id="newCategoryIcon" class="swal2-input" placeholder="Icono">
                                <input id="newCategoryTitle" class="swal2-input" placeholder="Título">
                            `,
                            focusConfirm: false,
                            preConfirm: () => {
                                const icon = document.getElementById('newCategoryIcon').value;
                                const title = document.getElementById('newCategoryTitle').value;
                                
                                // Aquí puedes agregar la lógica para guardar la nueva categoría en el servidor
                                console.log("Nueva categoría:", icon, title);
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "POST",
                                    url: "?uri=categories",
                                    data: {
                                        producto_id: idProducto,
                                        titulo: document.getElementById("propiedadTitulo").value,
                                        valor: document.getElementById("propiedadValor").value
                                    },
                                    success: function(response,data) {
                                        getProperties();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error de AJAX:", error);
                                        alert("Error en la comunicación con el servidor.");
                                    }
                                });
                            }
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Handle error response if needed
            }
        });

    });
    addTagButton.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });
    
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
                    <div class="category_item">
                    <div class="category_icon">
                    <i class="`+category.icon+`"></i>
                    </div>
                        <div class="category_name">
                            <h2>`+category.title+`</h2>
                            <small>`+category.projects+` posts en esta categoría</small>
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
            var tagsList = $('#tags_list');
            tagsList.empty(); // Clear existing items

            $.each(data, function(index, tag) {
                var item = `
                <div class="tag_item">
                <a href="#">#`+tag.title+`</a>
                </div>`;
                tagsList.append(item);
            });
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
})