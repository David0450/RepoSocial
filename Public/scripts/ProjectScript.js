/*const username = 'David0450';
const url = `https://api.github.com/users/${username}/repos`;


fetch(url)
  .then(response => {
    if (!response.ok) {
      throw new Error('Error en la solicitud');
    }
    return response.json();
  })
  .then(data => {
    console.log(data); // Aquí tienes los repositorios
    document.getElementById('projects_list').innerHTML = ''; // Limpiar la lista antes de agregar nuevos elementos
    data.forEach(repo => {
      const listItem = document.createElement('li');
      listItem.className = 'project-item';
      listItem.innerHTML = `
        <a href="${repo.html_url}" target="_blank">
            <img src="${repo.owner.avatar_url}" alt="${repo.name}" width="150" height="150">
            <h3>${repo.name}</h3>
            <p>${repo.description || 'Sin descripción'}</p>
            <p>${repo.language || 'Sin lenguaje'}</p>
            <p>${new Date(repo.created_at).toLocaleDateString()}</p>
        </a>
      `;
      document.getElementById('projects_list').appendChild(listItem);
    });
  })
  .catch(error => {
    console.error('Hubo un problema con la petición:', error);
  });
*/