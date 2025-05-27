document.addEventListener('DOMContentLoaded', () => {
    // Obtener usuarios registrados
    fetch(`${PATH}users/count`)
    .then(res => res.json())
    .then(data => {
        const userCount = document.getElementById('userCount');
        userCount.textContent = data.count;
    })

    // Obtener proyectos publicados
    fetch(`${PATH}projects/count`)
    .then(res => res.json())
    .then(data => {
        const projectCount = document.getElementById('projectCount');
        projectCount.textContent = data.count;
    })

    // Mostrar tabla
    let botonUsuarios = document.getElementById('dashboardUsers');
    let botonProyectos = document.getElementById('dashboardProjects');
    let botonCategories = document.getElementById('dashboardCategories');
    let botonTags = document.getElementById('dashboardTags');

    botonUsuarios.addEventListener('click', () => {
        document.getElementsByClassName('active')[0].classList.remove('active');
        botonUsuarios.classList.add('active');
        mostrarTabla('users');
    });

    botonProyectos.addEventListener('click', () => {
        document.getElementsByClassName('active')[0].classList.remove('active');
        botonProyectos.classList.add('active');
        mostrarTabla('projects');
    });

    botonCategories.addEventListener('click', () => {
        document.getElementsByClassName('active')[0].classList.remove('active');
        botonCategories.classList.add('active');
        mostrarTabla('categories');
    });

    botonTags.addEventListener('click', () => {
        document.getElementsByClassName('active')[0].classList.remove('active');
        botonTags.classList.add('active');
        mostrarTabla('tags');
    });

    botonUsuarios.click(); // Cargar usuarios por defecto al iniciar

    function mostrarTabla(tabla) {
        const contenedor = document.getElementById('dashboardTable');

        fetch(`${PATH}admin/${tabla}`)
        .then(res => res.json())
        .then(data => {
            contenedor.innerHTML = ''; // Limpiar contenido previo

            if (data.length === 0) {
                contenedor.innerHTML = '<p>No hay datos para mostrar.</p>';
                return;
            }

            const table = document.createElement('table');
            const thead = document.createElement('thead');
            const tbody = document.createElement('tbody');

            // Crear encabezados
            const headers = Object.keys(data[0]);
            const trHeader = document.createElement('tr');
            headers.forEach(header => {
                const th = document.createElement('th');
                th.textContent = header.charAt(0).toUpperCase() + header.slice(1);
                trHeader.appendChild(th);
            });
            thead.appendChild(trHeader);

            // Crear filas de datos
            data.forEach(item => {
                const tr = document.createElement('tr');
                headers.forEach(header => {
                    const td = document.createElement('td');
                    td.textContent = item[header];
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });

            table.appendChild(thead);
            table.appendChild(tbody);
            contenedor.appendChild(table);
        })
    }
});