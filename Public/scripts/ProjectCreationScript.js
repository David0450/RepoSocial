/*$(function() {
    $.ajax({
        url: '?uri=categories',
        method: 'GET',
        success: function(response) {
            const categories = JSON.parse(response);
            const categoryList = $('#categorySelect');
            categoryList.empty(); // Clear existing categories

            console.log(categories); // Debugging line to check the response

            categories.forEach(category => {
                const option = $('<option></option>').val(category.id).text(category.title);
                categoryList.append(option);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching categories:', error);
        }
    });

    const username = 'David0450';
    const url = `https://api.github.com/users/${username}/repos`;
    const token = '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';
    fetch(url, {
        headers: {
          'Authorization': `token ${token}`,
          'User-Agent': 'Techie',
        },
      }
    )
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la solicitud');
        }
        return response.json();
      })
      .then(data => {
        // Ordenar los repositorios por updated_at en orden descendente
        const sortedRepos = data.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));

        console.log(sortedRepos); // Aquí tienes los repositorios ordenados
        document.getElementById('projects_list').innerHTML = ''; // Limpiar la lista antes de agregar nuevos elementos
        sortedRepos.forEach(repo => {
          const listItem = document.createElement('li');
          listItem.className = 'project-item';
          listItem.innerHTML = `
            <a href="${repo.html_url}" target="_blank">
                <div class="project-image">
                    <img src="${repo.owner.avatar_url}" alt="${repo.name}" width="150" height="150">
                    <h3>${repo.name}</h3>
                </div>
                <div class="project-details">
                    <p>${repo.description || 'Sin descripción'}</p>
                    <p>${repo.language || 'Sin lenguaje'}</p>
                    <p>${new Date(repo.updated_at).toLocaleDateString()}</p>
                </div>
                <div class="project-languages">
                </div>
            </a>
          `;
          document.getElementById('projects_list').appendChild(listItem);

          const languagesUrl = `https://api.github.com/repos/${username}/${repo.name}/languages`;

          fetch(languagesUrl, {
            headers: {
              'Authorization': `token ${token}`,
              'User-Agent': 'Techie',
            },
          })
            .then(response => {
              if (!response.ok) {
                throw new Error('Error en la solicitud de lenguajes');
              }
              return response.json();
            })
            .then(languages => {
              const languagesList = listItem.querySelector('.project-languages');
              for (const [language, bytes] of Object.entries(languages)) {
                const languageItem = document.createElement('span');
                languageItem.className = 'language-item';
                languageItem.innerHTML = `<span style="color: var(--dodger-blue);">${language}</span> <span style="color: var(--jordy-blue);">(${bytes} bytes)</span>`;
                languagesList.appendChild(languageItem);
              }
            })
            .catch(error => {
              console.error('Hubo un problema con la petición de lenguajes:', error);
            });
        });
      })
      .catch(error => {
        console.error('Hubo un problema con la petición:', error);
      });
});*/
