$(function() {

    let sidebar = document.querySelector('.sidebar');
    let addCategoryButton = document.querySelector('#addCategoryButton');
    let addTagButton = document.querySelector('#addTagButton');

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

    if (addCategoryButton) {addCategoryButton.addEventListener('click', function() {
        $(document).on('click', '.deleteCategoryButton', function() {
            const categoryId = this.dataset.id; 

            Swal.fire({
                title: "¿Estás seguro de que deseas eliminar esta categoría?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                confirmButtonColor: "red",
                cancelButtonColor: "grey",
                theme: 'dark',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "?uri=categories",
                        data: { id: $(this).data("id") },
                        success: function(response) {
                            console.log("Categoría eliminada:", response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error de AJAX:", error);
                            alert("Error en la comunicación con el servidor.");
                        }
                    });
                }
            });
        });

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
                            <th class="border py-2 text-center">Icono</th>
                            <th class="border py-2 text-center">Título</th>
                            <th class="border py-2 text-center">Acciones</th>
                        </tr>
                        `+data.map(function(category) {
                            return `
                            <tr>
                                <td class="border text-center"><i class="${category.icon}"></i></td>
                                <td class="border fw-bold text-center">${category.title}</td>
                                <td class="border text-center" style="width: fit-content;">
                                    <button class="btn btn-danger deleteCategoryButton" data-id="${category.id}">Eliminar</button>
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

                                if (!icon || !title) {
                                    Swal.showValidationMessage('Por favor, completa todos los campos');
                                    return false;
                                }

                                return { icon, title };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const { icon, title } = result.value;
                                $.ajax({
                                    method: "POST",
                                    url: "?uri=categories",
                                    data: {
                                        title: title,
                                        icon: icon 
                                    },
                                    success: function(response) {
                                        console.log("Categoría añadida:", response);
                                        Swal.fire({
                                            icon: 'success',
                                            theme: 'dark',
                                            title: 'Categoría añadida',
                                            text: 'La categoría ha sido añadida con éxito.',
                                            confirmButtonText: 'Aceptar'
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error de AJAX:", error);
                                        Swal.fire({
                                            icon: 'error',
                                            theme: 'dark',
                                            title: 'Error',
                                            text: 'No se pudo añadir la categoría.',
                                            confirmButtonText: 'Aceptar'
                                        });
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
    })};

    if (addTagButton) {addTagButton.addEventListener('click', function() {
        $(document).on('click', '.deleteTagButton', function() {
            const tagId = this.dataset.id; 

            Swal.fire({
                title: "¿Estás seguro de que deseas eliminar esta etiqueta?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                confirmButtonColor: "red",
                cancelButtonColor: "grey",
                theme: 'dark',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "?uri=tags",
                        data: { id: $(this).data("id") },
                        success: function(response) {
                            console.log("Etiqueta eliminada:", response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error de AJAX:", error);
                            alert("Error en la comunicación con el servidor.");
                        }
                    });
                }
            });
        });

        $.ajax({
            url: '?uri=tags',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Swal.fire({
                    title: `Etiquetas`,
                    width: "50%",
                    theme: 'dark',
                    html: `
                    <table id="tablaPropiedades" class="w-100">
                        <tr class="text-white" style="background-color: var(--dodger-blue);">
                            <th class="border py-2 text-center">Título</th>
                            <th class="border py-2 text-center">Acciones</th>
                        </tr>
                        `+data.map(function(tag) {
                            return `
                            <tr>
                                <td class="border fw-bold text-center">${tag.title}</td>
                                <td class="border text-center" style="width: fit-content; ">
                                    <button class="btn btn-danger deleteCategoryButton" data-id="${tag.id}">Eliminar</button>
                                </td>
                            </tr>
                            `;
                        }).join('')+`
                    </table>
                    `,
                    icon: "info",
                    confirmButtonText: 'Añadir Etiqueta',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Añadir nueva etiqueta',
                            theme: 'dark',
                            html: `
                                <input id="newCategoryTitle" class="swal2-input" placeholder="Título">
                            `,
                            focusConfirm: false,
                            didOpen: () => {
                                const title = document.getElementById('newCategoryTitle');

                                title.addEventListener('change', () => {
                                    if (title.value.substr(0,1) !== '#') {
                                        let titleValue = title.value;
                                        title.value = '#'+titleValue;
                                    }
                                })
                            },
                            preConfirm: () => {
                                const title = document.getElementById('newCategoryTitle').value;

                                if (!title) {
                                    Swal.showValidationMessage('Por favor, completa todos los campos');
                                    return false;
                                }

                                return { title };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const { title } = result.value;
                                $.ajax({
                                    method: "POST",
                                    url: "?uri=tags",
                                    data: {
                                        title: title,
                                    },
                                    success: function(response) {
                                        console.log("Etiqueta añadida:", response);
                                        Swal.fire({
                                            icon: 'success',
                                            theme: 'dark',
                                            title: 'Etiqueta añadida',
                                            text: 'La etiqueta ha sido añadida con éxito.',
                                            confirmButtonText: 'Aceptar'
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error de AJAX:", error);
                                        Swal.fire({
                                            icon: 'error',
                                            theme: 'dark',
                                            title: 'Error',
                                            text: 'No se pudo añadir la etiqueta.',
                                            confirmButtonText: 'Aceptar'
                                        });
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
    })};
});
