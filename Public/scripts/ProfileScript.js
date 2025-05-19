document.addEventListener("DOMContentLoaded", () => {

    let userId;
    let totalRepos; // Declarar la variable fuera del alcance de las funciones

    fetch(`${BASE_PATH}users/@${USERNAME}/data`)
        .then(response => {
            if(!response.ok) {
                throw new Error('Error al obtener el id');
            }
            return response.json();
        })
        .then(response => {
            userId = response.id;
            fetch(`${BASE_PATH}users/@${USERNAME}/projects?userId=${encodeURIComponent(userId)}`)
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
                $.each(repos, function(index, repo) {
                    const listItem = document.createElement('li');
                    listItem.className = 'project-item';
                    const projectLink = document.createElement('div');

                    const projectImageDiv = document.createElement('div');
                    projectImageDiv.className = 'project-image';

                    const projectInfoDiv = document.createElement('div');
                    const projectTitle = document.createElement('h3');
                    projectTitle.textContent = repo.title;

                    const projectVisibility = document.createElement('small');
                    projectVisibility.textContent = repo.private === 0 ? 'Repositorio Público' : 'Repositorio Privado';
                    projectVisibility.textContent += ' en Github'

                    projectInfoDiv.appendChild(projectTitle);
                    projectInfoDiv.appendChild(projectVisibility);

                    projectImageDiv.appendChild(projectInfoDiv);

                    const projectDetailsDiv = document.createElement('div');
                    projectDetailsDiv.className = 'project-details';

                    const projectDescription = document.createElement('p');
                    projectDescription.textContent = repo.description || 'Sin descripción';

                    projectDetailsDiv.appendChild(projectDescription);

                    const projectActionsDiv = document.createElement('div');
                    projectActionsDiv.className = 'project-actions';

                    /*const uploadLink = document.createElement('a');
                    uploadLink.href = `${BASE_BASE_PATH}user/projects/upload?repo_id=${repo.id}`;*/

                    const uploadButton = document.createElement('button');
                    uploadButton.className = 'upload-btn';
                    uploadButton.innerHTML = '<span>Súbelo a tu perfil de Techie</span>';

                    //uploadLink.appendChild(uploadButton);

                    const githubLink = document.createElement('a');
                    githubLink.href = repo.html_url;
                    githubLink.target = '_blank';

                    const githubButton = document.createElement('button');
                    githubButton.className = 'social-btn';
                    githubButton.id = 'githubLoginButton';
                    githubButton.innerHTML = '<i class="fa-brands fa-github"></i><span>Ver en Github</span>';

                    githubLink.appendChild(githubButton);
                    
                    projectLink.appendChild(projectImageDiv);
                    projectLink.appendChild(projectDetailsDiv);
                    projectLink.appendChild(projectActionsDiv);

                    listItem.appendChild(projectLink);
                    document.getElementById('projects_list').appendChild(listItem);
                });

                fetch(`${BASE_PATH}users/@${USERNAME}/follow-stats`)
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

    fetch(`https://api.github.com/users/${USERNAME}`)
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
    

});
