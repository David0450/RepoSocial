let header = document.querySelector("header");
let loginButton = document.querySelector("#loginButton");
let githubLoginButton = document.querySelector("#githubLoginButton");
let logoContainer = document.querySelector("#headerLogoContainer");
let dropdownIcon = document.querySelector("#dropdownIcon");

let searchbar = document.querySelector("#searchbar");
let searchInput = document.querySelector("#search");
let mobileSearchInput = document.querySelector("#mobileSearchInput")
let searchResultsDiv = document.querySelector("#searchResults");
let searchResultsDivMobile = document.querySelector("#searchResultsMobile");
let searchPlaceholder = document.querySelector("#searchPlaceholder");
let userResults = document.querySelector("#userResultsSection");
let projectResults = document.querySelector("#projectResultsSection");


if (!loginButton && !githubLoginButton) {
    dropdownIcon.addEventListener("click", function() {
        let dropdown = document.querySelector("#dropdown");
        dropdown.classList.toggle("show");
        dropdownIcon.classList.toggle("rotate");
    });
    window.addEventListener("click", function(event) {
        if (!event.target.matches("#dropdownIcon") && !event.target.matches("#dropdownIcon *")) {
            let dropdown = document.querySelector("#dropdown");
            if (dropdown.classList.contains("show")) {
                dropdown.classList.remove("show");
                dropdownIcon.classList.remove("rotate");
            }
        }
    });
}

searchInput.addEventListener("focus", function() {
    searchPlaceholder.style.visibility = "hidden";
    if (searchInput.value !== "") {
        searchResultsDiv.style.display = "flex";
    }
});

searchInput.addEventListener("keyup", function() {
    if (searchInput.value !== "") {
        searchResultsDiv.style.display = "flex";
    }

    const input = searchInput.value.trim();
    if (input !== "") {
        fetch(`${PATH}/search?query=${encodeURIComponent(input)}`)
        .then(response => response.json())
        .then(data => {
            // Limpiar resultados anteriores
            userResults.innerHTML = "";
            projectResults.innerHTML = "";
            let noResults = document.querySelector("#noResults");
            noResults.textContent = "";

            // Mostrar usuarios
            if (data.users && data.users.length > 0) {
                let sectionTitle = document.createElement("h4");
                sectionTitle.textContent = "Usuarios";
                
                let resultsList = document.createElement("ul");
                resultsList.classList.add("user-results");
                
                data.users.forEach(user => {
                    const userLi = document.createElement("a");
                    userLi.href = `${PATH}@${user.username}`;

                    const userSpan = document.createElement("span");
                    userSpan.textContent = "@" + user.username;

                    const userImage = document.createElement("img");
                    userImage.src = user.avatar_url;

                    userLi.appendChild(userImage);
                    userLi.appendChild(userSpan);

                    resultsList.appendChild(userLi);
                });

                userResults.appendChild(sectionTitle);
                userResults.appendChild(resultsList);
            }

            // Mostrar proyectos
            if (data.projects && data.projects.length > 0) {
                let sectionTitle = document.createElement("h4");
                sectionTitle.textContent = "Projectos";
                
                let resultsList = document.createElement("ul");
                resultsList.classList.add("project-results");
                
                data.projects.forEach(project => {
                    const ProjectLi = document.createElement("a");
                    ProjectLi.href = project.html_url;
                    
                    const projectSpan = document.createElement("span");
                    projectSpan.textContent = project.title;
                    
                    const projectIcon = document.createElement("i");
                    projectIcon.classList.add("fa-solid", "fa-diagram-project");
                    
                    ProjectLi.appendChild(projectIcon);
                    ProjectLi.appendChild(projectSpan);
                    resultsList.appendChild(ProjectLi);
                });
                
                projectResults.appendChild(sectionTitle);
                projectResults.appendChild(resultsList);
            }

            // Si no hay resultados
            if ((!data.users || data.users.length === 0) && (!data.projects || data.projects.length === 0)) {
                noResults.textContent = "No se encontraron resultados.";
            }
        })
        .catch(error => {
            searchResultsDiv.innerHTML = "<div>Error al buscar resultados.</div>";
        });
    } else {
        searchResultsDiv.style.display = "none";
    }
})

searchInput.addEventListener("blur", function() {
    setTimeout(() => {
        if (searchInput.value !== "") {
            searchPlaceholder.style.visibility = "hidden";
        } else {
            searchPlaceholder.style.visibility = "visible";
        }

        searchResultsDiv.style.display = "none";
    }, 150);
});

  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('hamburgerToggle');
    const menu = document.getElementById('mobileMenu');
    toggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
    const toggleSearch = document.getElementById('toggleDropdownSearch');
    const searchContainer = document.getElementById('dropdownSearchContainer');
  
    if (toggleSearch && searchContainer) {
      toggleSearch.addEventListener('click', (e) => {
        e.preventDefault();
        searchContainer.classList.toggle('hidden');
      });
    }
  });

mobileSearchInput.addEventListener("focus", function() {
    searchPlaceholder.style.visibility = "hidden";
    if (mobileSearchInput.value !== "") {
        searchResultsDivMobile.style.display = "flex";
    }
});

mobileSearchInput.addEventListener("keyup", function() {
    if (mobileSearchInput.value !== "") {
        searchResultsDivMobile.style.display = "flex";
    }

    console.log("as")
    
    const input = mobileSearchInput.value.trim();
    if (input !== "") {
        fetch(`${PATH}/search?query=${encodeURIComponent(input)}`)
        .then(response => response.json())
        .then(data => {
            // Limpiar resultados anteriores
            userResults = document.querySelector("#userResultsMobile")
            userResults.innerHTML = "";
            projectResults = document.querySelector("#projectResultsMobile")
            projectResults.innerHTML = "";
            let noResults = document.querySelector("#noResultsMobile");
            noResults.textContent = "";
            
            // Mostrar usuarios
            if (data.users && data.users.length > 0) {
                let sectionTitle = document.createElement("h4");
                sectionTitle.textContent = "Usuarios";
                
                let resultsList = document.createElement("ul");
                resultsList.classList.add("user-results");
                
                data.users.forEach(user => {
                    const userLi = document.createElement("a");
                    userLi.href = `${PATH}@${user.username}`;
                    
                    const userSpan = document.createElement("span");
                    userSpan.textContent = "@" + user.username;
                    
                    const userImage = document.createElement("img");
                    userImage.src = PATH + user.avatar_url;
                    
                    userLi.appendChild(userImage);
                    userLi.appendChild(userSpan);
                    
                    resultsList.appendChild(userLi);
                });
                
                userResults.appendChild(sectionTitle);
                userResults.appendChild(resultsList);
            }
            
            // Mostrar proyectos
            if (data.projects && data.projects.length > 0) {
                let sectionTitle = document.createElement("h4");
                sectionTitle.textContent = "Projectos";
                
                let resultsList = document.createElement("ul");
                resultsList.classList.add("project-results");
                
                data.projects.forEach(project => {
                    const ProjectLi = document.createElement("a");
                    ProjectLi.href = project.html_url;
                    
                    const projectSpan = document.createElement("span");
                    projectSpan.textContent = project.title;
                    
                    const projectIcon = document.createElement("i");
                    projectIcon.classList.add("fa-solid", "fa-diagram-project");
                    
                    ProjectLi.appendChild(projectIcon);
                    ProjectLi.appendChild(projectSpan);
                    resultsList.appendChild(ProjectLi);
                });
                
                projectResults.appendChild(sectionTitle);
                projectResults.appendChild(resultsList);
            }
            
            // Si no hay resultados
            if ((!data.users || data.users.length === 0) && (!data.projects || data.projects.length === 0)) {
                noResults.textContent = "No se encontraron resultados.";
            }
        })
        .catch(error => {
            searchResultsDivMobile.innerHTML = "<div>Error al buscar resultados.</div>";
        });
    } else {
        searchResultsDivMobile.style.display = "none";
    }
})

mobileSearchInput.addEventListener("blur", function() {
    setTimeout(() => {
        if (mobileSearchInput.value !== "") {
            searchPlaceholder.style.visibility = "hidden";
        } else {
            searchPlaceholder.style.visibility = "visible";
        }
        
        searchResultsDivMobile.style.display = "none";
    }, 150);
});
