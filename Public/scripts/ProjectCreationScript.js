$(function() {
	const reposUrl = BASE_PATH+`users/@`+USERNAME+`/github-repos`;
	const uploadedReposUrl = BASE_PATH+`projects/getById`;
	const userUrl = BASE_PATH+`users/${USERNAME}/github/repos/count`;
	const categoriesUrl = BASE_PATH+`categories`;
	let currentPage = 0;
	let totalRepos = 0;

	function getProjects() {
		$.ajax({
			url: reposUrl,
			method: 'GET',
			dataType: 'json',
			success: function(data) {
				// Obtener categorías primero
				$.ajax({
					url: categoriesUrl,
					method: 'GET',
					dataType: 'json',
					success: function(categorias) {
						// Crear el select una sola vez
						const categoriesSelect = document.createElement('select');
						categoriesSelect.className = 'categories-select';


						categorias.forEach(function(category) {
							const option = document.createElement('option');
							option.value = category.id;
							option.textContent = category.title;
							categoriesSelect.appendChild(option);
						});
						// Selecciona la primera opción (la de "Selecciona una categoria")
						categoriesSelect.id = 'categoriesSelect';

						// Ahora renderiza los proyectos
						const repos = JSON.parse(data['repos']);
						totalRepos = data['total'];
						document.getElementById('projects_list').innerHTML = '';
						$.each(repos, function(index, repo) {
							const listItem = document.createElement('li');
							listItem.className = 'project-item';
							const projectLink = document.createElement('div');

							const projectImageDiv = document.createElement('div');
							projectImageDiv.className = 'project-image';

							const projectImage = document.createElement('img');
							projectImage.src = repo.owner.avatar_url;
							projectImage.alt = repo.name;
							projectImage.width = 150;
							projectImage.height = 150;

							const projectInfoDiv = document.createElement('div');
							const projectTitle = document.createElement('h3');
							projectTitle.textContent = repo.name;

							const projectVisibility = document.createElement('small');
							projectVisibility.textContent = repo.visibility === 'public' ? 'Repositorio Público' : 'Repositorio Privado';
							projectVisibility.textContent += ' en Github';

							projectInfoDiv.appendChild(projectTitle);
							projectInfoDiv.appendChild(projectVisibility);

							projectImageDiv.appendChild(projectImage);
							projectImageDiv.appendChild(projectInfoDiv);

							const projectDetailsDiv = document.createElement('div');
							projectDetailsDiv.className = 'project-details';

							const projectDescription = document.createElement('p');
							projectDescription.textContent = repo.description || 'Sin descripción';

							projectDetailsDiv.appendChild(projectDescription);

							const projectActionsDiv = document.createElement('div');
							projectActionsDiv.className = 'project-actions';

							// Clona el select para cada proyecto
							const selectClone = categoriesSelect.cloneNode(true);
							projectActionsDiv.appendChild(selectClone);

							const uploadLink = document.createElement('a');
							uploadLink.href = `${BASE_PATH}users/@${USERNAME}/github-repos/import?repo_id=${repo.id}&categorie=${selectClone.value}`;

							const uploadButton = document.createElement('button');
							uploadButton.className = 'upload-btn';
							uploadButton.innerHTML = '<span>Súbelo a tu perfil de Techie</span>';

							const githubLink = document.createElement('a');
							githubLink.href = repo.html_url;
							githubLink.target = '_blank';

							const githubButton = document.createElement('button');
							githubButton.className = 'social-btn';
							githubButton.id = 'githubLoginButton';
							githubButton.innerHTML = '<i class="fa-brands fa-github"></i><span>Ver en Github</span>';

							githubLink.appendChild(githubButton);

							$(selectClone).change( () => {
								uploadLink.href = `${BASE_PATH}users/@${USERNAME}/github-repos/import?repo_id=${repo.id}&categorie=${selectClone.value}`;
							})

							// Check if the repository exists in uploadedReposUrl
							$.ajax({
								url: `${uploadedReposUrl}?repo_id=${repo.id}`,
								method: 'GET',
								dataType: 'json',
								success: function(uploadedData) {
									if (uploadedData.length != 0) {
										const alreadyUploaded = document.createElement('button');
										alreadyUploaded.className = 'uploaded-btn';
										alreadyUploaded.setAttribute('disabled', '');
										alreadyUploaded.innerHTML = '<span>Ya esta subido</span>';
										uploadLink.appendChild(alreadyUploaded);
									} else {
										uploadLink.appendChild(uploadButton);
									}
								},
								error: function(xhr, status, error) {
									console.error(`Error checking uploaded repository for ID ${repo.id}:`, error);
								},
								complete: function() {
									projectActionsDiv.appendChild(uploadLink);
									projectActionsDiv.appendChild(githubLink);
								}
							});

							projectLink.appendChild(projectImageDiv);
							projectLink.appendChild(projectDetailsDiv);
							projectLink.appendChild(projectActionsDiv);

							listItem.appendChild(projectLink);
							document.getElementById('projects_list').appendChild(listItem);
						});
					}
				});
			},
			error: function(xhr, status, error) {
				console.error('Error fetching projects:', error);
			}
		});
	}

	// ... resto del código igual ...

	getProjects();
});
