document.addEventListener("DOMContentLoaded", () => {
    
    let userId;
    let totalRepos; // Declarar la variable fuera del alcance de las funciones
    const editUsernameButton = document.querySelector("#editUsernameButton");

    // Obtener los datos del usuario
    fetch(`${BASE_PATH}users/@${PROFILE_USERNAME}/data`)
        .then(response => {
            if(!response.ok) {
                throw new Error('Error al obtener el id');
            }
            return response.json();
        })
        .then(response => {
            userId = response.id;
            fetch(`${BASE_PATH}users/@${PROFILE_USERNAME}/projects?userId=${encodeURIComponent(userId)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener proyectos');
                }
                return response.json();
            })
            .then(data => {
                repos = data['repos'];
                totalRepos = data['total']; // Asignar el valor a la variable totalRepos
                document.getElementById('projects_list').innerHTML = ''; // Limpiar la lista antes de agregar nuevos elementos

                if (totalRepos === 0) {
                    document.getElementById('projects_list').style.display = 'none';

                    const emptyProjects = document.createElement('div');
                    emptyProjects.className = 'empty_projects';

                    const emptyMsg = document.createElement('span');
                    emptyMsg.style.fontSize = '1.3rem';
                    emptyMsg.textContent = '¡Ups! Aquí no hay nada...';

                    emptyProjects.appendChild(emptyMsg);

                    if (USER_USERNAME == PROFILE_USERNAME) {

                        const uploadLink = document.createElement('a');
                        uploadLink.href = `${BASE_PATH}users/@${PROFILE_USERNAME}/github-repos/view`;
                        
                        const uploadBtn = document.createElement('button');
                        uploadBtn.className = 'create_project_button';
                        uploadBtn.textContent = 'Mis repositorios';
                        
                        uploadLink.appendChild(uploadBtn);
                        emptyProjects.appendChild(uploadLink);
                    }


                    document.getElementsByClassName('profile_projects')[0].appendChild(emptyProjects);

                }
                $.each(repos, function(index, repo) {
                    // Crear la tarjeta del proyecto
                    const card = document.createElement('div');
                    card.className = 'project_card';

                    // Contenido principal de la tarjeta
                    const content = document.createElement('div');
                    content.className = 'project_card_content';

                    // Título y visibilidad
                    const titleRow = document.createElement('div');
                    titleRow.className = 'project_card_title_row';

                    const title = document.createElement('h3');
                    title.textContent = repo.title || repo.name || 'Sin título';

                    const visibility = document.createElement('span');
                    visibility.className = 'project_card_visibility';
                    visibility.textContent = repo.private === 0 ? 'Público' : 'Privado';

                    titleRow.appendChild(title);
                    titleRow.appendChild(visibility);

                    // Descripción
                    const description = document.createElement('p');
                    description.className = 'project_card_description';
                    description.textContent = (repo.description == null || repo.description == '') ? 'Sin descripción' : repo.description.slice(0, 120) + (repo.description.length > 120 ? '...' : '');

                    // Stats
                    const stats = document.createElement('div');
                    stats.className = 'project_card_stats';

                    // Likes
				    const likesDiv = document.createElement('div');
				    likesDiv.title = 'Likes';
				    const likeIcon = document.createElement('i');
				    likeIcon.className = 'fa-solid fa-heart like-button';
				    const likeSpan = document.createElement('span');
				    likeSpan.textContent = repo.like_count ?? 0;
				    likesDiv.appendChild(likeIcon);
				    likesDiv.appendChild(likeSpan);

                    // Comments
				    const commentsDiv = document.createElement('div');
				    commentsDiv.title = 'Comentarios';
				    const commentIcon = document.createElement('i');
				    commentIcon.className = 'fa-solid fa-comments comment-button';
				    const commentSpan = document.createElement('span');
				    commentSpan.textContent = repo.comment_count ?? 0;
				    commentsDiv.appendChild(commentIcon);
				    commentsDiv.appendChild(commentSpan);
                    
                    stats.appendChild(likesDiv);
                    stats.appendChild(commentsDiv);

                    const menuContainer = document.createElement('div');

                    // Crear el menú desplegable
                    const dropdownMenu = document.createElement('div');
                    dropdownMenu.className = 'card_dropdown_menu';
                    dropdownMenu.style.display = 'none';

                    // Opción Eliminar
                    const deleteOption = document.createElement('button');
                    deleteOption.className = 'dropdown_delete_btn';
                    deleteOption.textContent = 'Eliminar';

                    // Evento para eliminar el proyecto
                    deleteOption.addEventListener('click', () => {
                        // Crear overlay del modal
                        const modalOverlay = document.createElement('div');
                        modalOverlay.className = 'modal-overlay';

                        // Crear el modal
                        const modal = document.createElement('div');
                        modal.className = 'custom-modal';

                        const modalTitle = document.createElement('h2');
                        modalTitle.textContent = '¿Seguro que quieres eliminar este proyecto?';

                        const modalButtons = document.createElement('div');
                        modalButtons.style.display = 'flex';
                        modalButtons.style.justifyContent = 'flex-end';
                        modalButtons.style.gap = '10px';

                        const cancelButton = document.createElement('button');
                        cancelButton.textContent = 'Cancelar';
                        cancelButton.className = 'btn-cancelar';

                        const confirmButton = document.createElement('button');
                        confirmButton.textContent = 'Eliminar';
                        confirmButton.className = 'btn-eliminar';

                        modalButtons.appendChild(cancelButton);
                        modalButtons.appendChild(confirmButton);

                        modal.appendChild(modalTitle);
                        modal.appendChild(modalButtons);
                        modalOverlay.appendChild(modal);
                        document.body.appendChild(modalOverlay);

                        // Cerrar modal al hacer click en cancelar o fuera del modal
                        cancelButton.addEventListener('click', () => {
                            document.body.removeChild(modalOverlay);
                        });
                        modalOverlay.addEventListener('click', (e) => {
                            if (e.target === modalOverlay) {
                                document.body.removeChild(modalOverlay);
                            }
                        });

                        // Eliminar proyecto al confirmar
                        confirmButton.addEventListener('click', () => {
                            const formData = new FormData();
                            formData.append('id', repo.id);
                            fetch(`${BASE_PATH}projects/delete`, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) throw new Error('No se pudo eliminar el proyecto');
                                card.remove();
                                document.body.removeChild(modalOverlay);
                            })
                            .catch(error => {
                                alert('Error al eliminar el proyecto');
                                console.error(error);
                                document.body.removeChild(modalOverlay);
                            });
                        });

                        dropdownMenu.style.display = 'none';
                    });

                    dropdownMenu.appendChild(deleteOption);

                    // Mostrar/ocultar menú al hacer click en el botón
                    menuContainer.addEventListener('click', (e) => {
                        e.stopPropagation();
                        dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
                    });

                    // Ocultar menú si se hace click fuera
                    document.addEventListener('click', () => {
                        dropdownMenu.style.display = 'none';
                    });

                    menuContainer.className = 'project_card_menu';
                    
                    const menuButton = document.createElement('button');
                    menuButton.className = 'card_menu_button';
                    menuButton.innerHTML = '<i class="fa-solid fa-ellipsis-vertical"></i>';
                    
                    menuContainer.appendChild(menuButton);
                    menuContainer.appendChild(dropdownMenu);

                    // Acciones
                    const actions = document.createElement('div');
                    actions.className = 'project_card_actions';

                    const githubLink = document.createElement('a');
                    githubLink.href = repo.html_url;
                    githubLink.target = '_blank';
                    githubLink.className = 'project_card_btn github';
                    
                    const githubIcon = document.createElement('i');
                    githubIcon.className = 'fa-brands fa-github';
                    githubLink.appendChild(githubIcon);
                    githubLink.appendChild(document.createTextNode(' Ver en Github'));
                    
                    actions.appendChild(githubLink);
                    
                    // Montar la tarjeta
                    content.appendChild(titleRow);
                    content.appendChild(description);
                    content.appendChild(stats);
                    content.appendChild(actions);
                    content.appendChild(menuContainer);
                    
                    card.appendChild(content);
                    
                    // Agregar la tarjeta a la lista
                    document.getElementById('projects_list').appendChild(card);

                });

                // Obtener el número de seguidores y seguidos
                fetch(`${BASE_PATH}users/@${PROFILE_USERNAME}/follow-stats`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error');
                        }
                        return response.json();
                    })
                    .then(response => {
                        let followersCount = document.getElementById('followersCount');
                        followersCount.textContent = '';
                    
                        const followersSpan = document.createElement('span');
                        followersSpan.textContent = 'Seguidores';
                    
                        const followersNum = document.createElement('span');
                        followersNum.textContent = response.followers;

                        followersCount.appendChild(followersSpan);
                        followersCount.appendChild(followersNum);

                        let followsCount = document.getElementById('followingCount');
                        followsCount.textContent = '';
                    
                        const followsSpan = document.createElement('span');
                        followsSpan.textContent = 'Siguiendo';
                    
                        const followsNum = document.createElement('span');
                        followsNum.textContent = response.follows;

                        followsCount.appendChild(followsSpan);
                        followsCount.appendChild(followsNum);
                    
                        let postsCount = document.getElementById('postsCount');
                        postsCount.textContent = '';

                        const postsSpan = document.createElement('span');
                        postsSpan.textContent = 'Publicaciones';

                        const postsNum = document.createElement('span');
                        postsNum.textContent = totalRepos;

                        postsCount.appendChild(postsSpan);
                        postsCount.appendChild(postsNum);
                    });
            });
        });

    // Obtener la biografía del usuario
    fetch(`https://api.github.com/users/${PROFILE_USERNAME}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Usuario no encontrado');
            }
            return response.json();
        })
        .then(user => {
            let userBio = document.getElementById('userBio');
            userBio.textContent = user.bio;
        });
    
    let followButton = document.getElementById('followButton');
    if (followButton) {
        followButton.addEventListener('click', function() {
            const followerId = parseInt(USER_ID);
            const followedId = parseInt(userId);

            fetch(`${BASE_PATH}users/@${PROFILE_USERNAME}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `followerId=${encodeURIComponent(followerId)}&followedId=${encodeURIComponent(followedId)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al seguir al usuario');
                }
                return response.json();
            })
            .then(data => {
                alert('Ahora sigues a este usuario');
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }

        
        if (editUsernameButton) {
            editUsernameButton.addEventListener("click", function() {
                const modalOverlay = document.createElement('div');
                modalOverlay.classList.add('modal-overlay');

                const modal = document.createElement('div');
                modal.classList.add('change-photo-modal');

                // Crear los elementos del modal usando createElement
                const modalTitle = document.createElement('h2');
                modalTitle.textContent = 'Cambia tu nombre de usuario';

                const usernameInput = document.createElement('input');
                usernameInput.type = 'text';
                usernameInput.placeholder = 'Nuevo nombre de usuario';
                usernameInput.id = 'newUsernameInput';
                usernameInput.value = PROFILE_USERNAME;
                
                const saveButton = document.createElement('button');
                saveButton.classList.add('btn-guardar');
                saveButton.textContent = 'Guardar';
                
                saveButton.addEventListener('click', () => {
                    const newUsername = usernameInput.value;
                    if (newUsername && newUsername.trim() !== "") {
                        fetch(`${BASE_PATH}users/@${PROFILE_USERNAME}/edit-username`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `newUsername=${encodeURIComponent(newUsername)}`
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("No se pudo actualizar el nombre de usuario");
                            }
                            return response.json();
                        })
                        .then(() => {
                            window.location.href = `${BASE_PATH}@${newUsername}`;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    } else {
                        // Mostrar mensaje de error debajo del input
                        let errorMsg = modal.querySelector('.username-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('div');
                            errorMsg.className = 'username-error';
                            errorMsg.style.color = 'red';
                            errorMsg.style.marginTop = '8px';
                            modal.appendChild(errorMsg);
                        }
                        errorMsg.textContent = "El nombre de usuario no puede estar vacío.";
                    }
                });

                modalOverlay.addEventListener('click', (e) => {
                    if (e.target === modalOverlay) {
                        document.body.removeChild(modalOverlay);
                    }
                });
                
                modal.appendChild(modalTitle);
                modal.appendChild(usernameInput);
                modal.appendChild(saveButton);
                
                modalOverlay.appendChild(modal);
                document.body.appendChild(modalOverlay);
            });
        }

    const profileAvatar = document.getElementById('profileAvatar');
    const changePhotoOverlay = document.getElementById('changePhotoOverlay');
    if (profileAvatar && changePhotoOverlay) {
        changePhotoOverlay.addEventListener('click', () => {
            // Crear el modal
            const modalOverlay = document.createElement('div');
            modalOverlay.classList.add('modal-overlay');

            const modal = document.createElement('div');
            modal.classList.add('change-photo-modal');

            // Crear los elementos del modal usando createElement
            const modalTitle = document.createElement('h2');
            modalTitle.textContent = 'Selecciona una imagen';

            const dropZone = document.createElement('div');
            dropZone.id = 'dropZone';

            const imageIcon = document.createElement('i');
            imageIcon.className = 'fa-solid fa-image';
            imageIcon.style.fontSize = '2.5rem';

            const dropZoneText = document.createTextNode('Sube una imagen');

            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.style.display = 'none';
            fileInput.id = 'fileInput';

            dropZone.appendChild(imageIcon);
            dropZone.appendChild(document.createElement('br'));
            dropZone.appendChild(dropZoneText);
            dropZone.appendChild(fileInput);

            const saveButton = document.createElement('button');
            saveButton.classList.add('btn-guardar');
            saveButton.textContent = 'Guardar';

            modal.appendChild(modalTitle);
            modal.appendChild(dropZone);
            modal.appendChild(saveButton);

            modalOverlay.appendChild(modal);
            document.body.appendChild(modalOverlay);

            // Drag and drop y selección de archivo
            dropZone.addEventListener('click', () => fileInput.click());
            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.style.background = '#f0f0f0';
            });
            dropZone.addEventListener('dragleave', e => {
                e.preventDefault();
                dropZone.style.background = '';
            });
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.style.background = '';
                const files = e.dataTransfer.files;
                if (files && files[0]) {
                    fileInput.files = files;

                    // Mostrar vista previa de la imagen en la dropzone
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            // Elimina cualquier imagen previa
                            const existingImg = dropZone.querySelector('img');
                            if (existingImg) {
                                dropZone.removeChild(existingImg);
                            }
                            // Crea la imagen de vista previa
                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.style.width = '100%';
                            img.style.aspectRatio = '1/1';
                            img.style.position = 'absolute';
                            img.style.top = '0';
                            img.style.left = '0';
                            dropZone.textContent = '';
                            dropZone.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            fileInput.addEventListener('change', (e) => {
                dropZone.style.background = '';
                
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            // Elimina cualquier imagen previa
                            const existingImg = dropZone.querySelector('img');
                            if (existingImg) {
                                dropZone.removeChild(existingImg);
                            }
                            // Crea la imagen de vista previa
                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.style.width = '100%';
                            img.style.aspectRatio = '1/1';
                            img.style.position = 'absolute';
                            img.style.top = '0';
                            img.style.left = '0';
                            // Limpia el contenido del dropZone y agrega la imagen
                            dropZone.textContent = '';
                            dropZone.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            saveButton.addEventListener('click', () => {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    // Crear un nuevo archivo con el nombre PROFILE_USERNAME y la misma extensión
                    const extension = file.name.split('.').pop();
                    const newFileName = `${PROFILE_USERNAME}.${extension}`;
                    const renamedFile = new File([file], newFileName, { type: file.type });

                    const formData = new FormData();
                    formData.append('imagen', renamedFile);

                    fetch(`${BASE_PATH}users/@${PROFILE_USERNAME}/upload-profile-image`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al subir la imagen');
                        }
                        return response.json();
                    })
                    .then(data => {
                        location.reload();                        
                        document.body.removeChild(modalOverlay);
                    })
                    .catch(error => {
                        console.error(error);
                    });
                }
            });

            // Cerrar el modal al hacer click fuera de él
            modalOverlay.addEventListener('click', (e) => {
                if (e.target === modalOverlay) {
                    document.body.removeChild(modalOverlay);
                }
            });
        });
    }
});
