$(function() {
	const reposUrl = BASE_PATH+`user/projects/get`;
	const uploadedReposUrl = BASE_PATH+`user/projects/getUploaded`;
	const userUrl = BASE_PATH+`user/projects/count`;
	let currentPage = 0;
	let totalRepos = 0;

	function getProjects() {
		$.ajax({
			url: reposUrl,
			method: 'GET',
			dataType: 'json',
			success: function(data) {
				repos = JSON.parse(data['repos']);
				totalRepos = data['total'];
				document.getElementById('projects_list').innerHTML = ''; // Limpiar la lista antes de agregar nuevos elementos
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

					/*const uploadLink = document.createElement('a');
					uploadLink.href = `${BASE_PATH}user/projects/upload?repo_id=${repo.id}`;*/

					const uploadButton = document.createElement('button');
					uploadButton.className = 'upload-btn';
					uploadButton.innerHTML = '<span>Súbelo a tu perfil de Techie</span>';

					uploadLink.appendChild(uploadButton);

					const githubLink = document.createElement('a');
					githubLink.href = repo.html_url;
					githubLink.target = '_blank';

					const githubButton = document.createElement('button');
					githubButton.className = 'social-btn';
					githubButton.id = 'githubLoginButton';
					githubButton.innerHTML = '<i class="fa-brands fa-github"></i><span>Ver en Github</span>';

					githubLink.appendChild(githubButton);

					
					// Check if the repository exists in uploadedReposUrl
					$.ajax({
						url: `${uploadedReposUrl}?repo_id=${repo.id}`,
						method: 'GET',
						dataType: 'json',
						success: function(uploadedData) {
							console.log(uploadedData);
							if (uploadedData.length != 0) {
								const alreadyUploaded = document.createElement('button');
								alreadyUploaded.className = 'uploaded-btn';
								alreadyUploaded.setAttribute('disabled', '');
								alreadyUploaded.innerHTML = '<span>Ya esta subido</span>';
								projectActionsDiv.appendChild(alreadyUploaded);
							} else {
								projectActionsDiv.appendChild(uploadButton);
							}
						},
						error: function(xhr, status, error) {
							console.error(`Error checking uploaded repository for ID ${repo.id}:`, error);
						},
						complete: function() {
							projectActionsDiv.appendChild(githubLink);
						}
					});


					projectLink.appendChild(projectImageDiv);
					projectLink.appendChild(projectDetailsDiv);
					projectLink.appendChild(projectActionsDiv);

					listItem.appendChild(projectLink);
					document.getElementById('projects_list').appendChild(listItem);
				});
			},
			error: function(xhr, status, error) {
				console.error('Error fetching projects:', error);
			}
		});
	}

	/*function getProjects() {
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				repos = data['repos'];
				console.log(repos); // Debugging line to check the response
				totalRepos = data['total'];
				document.getElementById('projects_list').innerHTML = ''; // Limpiar la lista antes de agregar nuevos elementos
				$.each(repos, function(index, repo) {
					const listItem = document.createElement('li');
					listItem.className = 'project-item';
					listItem.innerHTML = `
					<a href="${repo.html_url}" target="_blank">
						<div class="project-image">
							<img src="${repo.owner_avatar}" alt="${repo.name}" width="150" height="150">
							<div>
								<h3>${repo.title}</h3>
								<small>${repo.private ? 'Repositorio Privado' : 'Repositorio Público'}</small>
							</div>
						</div>
						<div class="project-details">
							<p>${repo.description || 'Sin descripción'}</p>
							<p>${repo.language || 'Sin lenguaje'}</p>
							<p>${new Date(repo.created_at).toLocaleDateString()}</p>
						</div>
						<div class="project-languages">
						</div>
					</a>
					`;
					document.getElementById('projects_list').appendChild(listItem);
				});
				

				let paginationInfo = document.getElementById('pageInfo');
				paginationInfo.innerHTML = `Página ${currentPage+1} de ${Math.ceil(totalRepos / limit)}`;
			},
			error: function(xhr, status, error) {
				console.error('Error fetching projects:', error);
			}
		});
	}*/

	// Registrar eventos de los botones una sola vez
	$('#nextPage').on('click', function() {
		console.log(currentPage, totalRepos, limit);

		if (currentPage >= Math.ceil(totalRepos / limit) - 1) {
			return; // No hacer nada si ya estamos en la última página
		} else {
			currentPage++;
			url = `http://localhost/programacion/PFC/user/projects/get?offset=${currentPage * limit}`;
			getProjects();
		}
	});

	$('#prevPage').on('click', function() {
		if (currentPage > 0) {
			currentPage--;
			url = `http://localhost/programacion/PFC/user/projects/get?offset=${currentPage * limit}`;
			getProjects();
		}
	});

	getProjects();
});
