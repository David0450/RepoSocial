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

    // Activar por defecto la tabla de usuarios
    botonUsuarios.classList.add('active');
    mostrarTabla('users');

    function mostrarTabla(tabla) {
        const contenedor = document.getElementById('dashboardTable');
        let currentPage = 1;
        const pageSize = 10;

        function fetchAndRender(page = 1) {
            fetch(`${PATH}admin/${tabla}?page=${page}&limit=${pageSize}`)
            .then(res => res.json())
            .then(result => {
                let data, total;
                if (Array.isArray(result)) {
                    data = result;
                    total = result.length;
                } else {
                    data = result.data;
                    total = result.total;
                }

                contenedor.innerHTML = '';

                // Botón "Crear" solo para tags y categories
                if (tabla === 'tags' || tabla === 'categories') {
                    const crearDiv = document.createElement('div');
                    crearDiv.style.marginBottom = '10px';
                    const btnCrear = document.createElement('button');
                    btnCrear.textContent = `Crear`;
                    btnCrear.className = 'btn-crear';
                    btnCrear.onclick = () => {
                        // Modal para crear nuevo registro
                        let modal = document.createElement('div');
                        modal.style.position = 'fixed';
                        modal.style.top = '0';
                        modal.style.left = '0';
                        modal.style.width = '100vw';
                        modal.style.height = '100vh';
                        modal.style.background = 'rgba(0,0,0,0.5)';
                        modal.style.display = 'flex';
                        modal.style.alignItems = 'center';
                        modal.style.justifyContent = 'center';
                        modal.style.zIndex = '9999';

                        let modalContent = document.createElement('div');
                        modalContent.className = 'custom-modal';

                        let form = document.createElement('form');
                        form.onsubmit = function(e) {
                            e.preventDefault();
                            let nuevo = {};
                            inputs.forEach(input => {
                                nuevo[input.name] = input.value;
                            });
                            const formData = new FormData();
                            Object.keys(nuevo).forEach(key => {
                                formData.append(key, nuevo[key]);
                            });
                            fetch(`${PATH}${tabla}`, {
                                method: 'POST',
                                body: formData
                            })
                            .then(() => {
                                document.body.removeChild(modal);
                                fetchAndRender(currentPage);
                            });
                        };

                        // Usar los headers de la tabla actual (sin projects ni id)
                        let headersCrear = data && data.length > 0
                            ? Object.keys(data[0]).filter(h => h !== 'projects' && h !== 'id')
                            : (tabla === 'tags' ? ['name'] : ['name']);
                        let inputs = [];
                        headersCrear.forEach(header => {
                            let label = document.createElement('label');
                            label.textContent = header.charAt(0).toUpperCase() + header.slice(1);
                            label.style.display = 'block';
                            label.style.marginTop = '10px';
                            let input = document.createElement('input');
                            input.type = 'text';
                            input.name = header;
                            input.style.width = '100%';
                            input.style.marginTop = '5px';
                            inputs.push(input);
                            form.appendChild(label);
                            form.appendChild(input);
                        });

                        let btnGuardar = document.createElement('button');
                        btnGuardar.type = 'submit';
                        btnGuardar.className = 'btn-guardar';
                        btnGuardar.textContent = 'Guardar';
                        btnGuardar.style.marginTop = '15px';
                        btnGuardar.style.marginRight = '10px';

                        let btnCancelar = document.createElement('button');
                        btnCancelar.type = 'button';
                        btnCancelar.className = 'btn-cancelar';
                        btnCancelar.textContent = 'Cancelar';
                        btnCancelar.style.marginTop = '15px';
                        btnCancelar.onclick = function() {
                            document.body.removeChild(modal);
                        };

                        form.appendChild(btnGuardar);
                        form.appendChild(btnCancelar);

                        modalContent.appendChild(form);
                        modal.appendChild(modalContent);
                        document.body.appendChild(modal);
                    };
                    crearDiv.appendChild(btnCrear);
                    contenedor.appendChild(crearDiv);
                }

                if (!data || data.length === 0) {
                    contenedor.innerHTML += '<p>No hay datos para mostrar.</p>';
                    return;
                }

                // Quitar columna 'projects' si tabla es tags o categories
                let headers = Object.keys(data[0]);
                if (tabla === 'tags' || tabla === 'categories') {
                    headers = headers.filter(h => h !== 'projects');
                    data = data.map(item => {
                        const { projects, ...rest } = item;
                        return rest;
                    });
                }

                // Crear input de búsqueda
                const searchDiv = document.createElement('div');
                searchDiv.style.marginBottom = '10px';
                const searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.placeholder = 'Buscar...';
                searchInput.style.padding = '5px';
                searchInput.style.width = '200px';
                searchDiv.appendChild(searchInput);
                contenedor.appendChild(searchDiv);

                const table = document.createElement('table');
                const thead = document.createElement('thead');
                const tbody = document.createElement('tbody');

                // Crear encabezados
                const trHeader = document.createElement('tr');
                headers.forEach(header => {
                    const th = document.createElement('th');
                    th.textContent = header.charAt(0).toUpperCase() + header.slice(1);
                    trHeader.appendChild(th);
                });
                // Agregar columna de acciones
                const thAcciones = document.createElement('th');
                thAcciones.textContent = 'Acciones';
                trHeader.appendChild(thAcciones);

                thead.appendChild(trHeader);

                // Función para renderizar filas según filtro
                function renderRows(filteredData) {
                    tbody.innerHTML = '';
                    filteredData.forEach(item => {
                        const tr = document.createElement('tr');
                        headers.forEach(header => {
                            const td = document.createElement('td');
                            let text = item[header];
                            if (typeof text === 'string' && text.length > 25) {
                                td.textContent = text.slice(0, 25) + '...';
                                td.title = text;
                            } else {
                                td.textContent = text;
                            }
                            tr.appendChild(td);
                        });

                        // Columna de acciones
                        const tdAcciones = document.createElement('td');
                        const btnEditar = document.createElement('button');
                        btnEditar.textContent = 'Editar';
                        btnEditar.className = 'btn-editar';
                        btnEditar.onclick = () => {
                            // Crear modal para editar el registro
                            let modal = document.createElement('div');
                            modal.style.position = 'fixed';
                            modal.style.top = '0';
                            modal.style.left = '0';
                            modal.style.width = '100vw';
                            modal.style.height = '100vh';
                            modal.style.background = 'rgba(0,0,0,0.5)';
                            modal.style.display = 'flex';
                            modal.style.alignItems = 'center';
                            modal.style.justifyContent = 'center';
                            modal.style.zIndex = '9999';

                            let modalContent = document.createElement('div');
                            modalContent.className = 'custom-modal';

                            let form = document.createElement('form');
                            form.onsubmit = function(e) {
                                e.preventDefault();
                                let updated = {};
                                inputs.forEach(input => {
                                    updated[input.name] = input.value;
                                });
                                fetch(`${PATH}admin/${tabla}/${item[headers[0]]}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(updated)
                                })
                                .then(() => {
                                    document.body.removeChild(modal);
                                    fetchAndRender(currentPage);
                                });
                            };

                            let inputs = [];
                            headers.forEach((header, idx) => {
                                let label = document.createElement('label');
                                label.textContent = header.charAt(0).toUpperCase() + header.slice(1);
                                label.style.display = 'block';
                                label.style.marginTop = '10px';
                                let input = document.createElement('input');
                                input.type = 'text';
                                input.name = header;
                                input.value = item[header];
                                input.style.width = '100%';
                                input.style.marginTop = '5px';
                                if (idx === 0) {
                                    input.readOnly = true;
                                    input.style.background = '#eee';
                                    input.style.cursor = 'not-allowed';
                                    input.style.color = '#666';
                                    input.style.userSelect = 'none';
                                    input.tabIndex = -1;
                                } else {
                                    inputs.push(input);
                                }
                                form.appendChild(label);
                                form.appendChild(input);
                            });

                            let btnGuardar = document.createElement('button');
                            btnGuardar.type = 'submit';
                            btnGuardar.className = 'btn-guardar';
                            btnGuardar.textContent = 'Guardar';
                            btnGuardar.style.marginTop = '15px';
                            btnGuardar.style.marginRight = '10px';

                            let btnCancelar = document.createElement('button');
                            btnCancelar.type = 'button';
                            btnCancelar.className = 'btn-cancelar';
                            btnCancelar.textContent = 'Cancelar';
                            btnCancelar.style.marginTop = '15px';
                            btnCancelar.onclick = function() {
                                document.body.removeChild(modal);
                            };

                            form.appendChild(btnGuardar);
                            form.appendChild(btnCancelar);

                            modalContent.appendChild(form);
                            modal.appendChild(modalContent);
                            document.body.appendChild(modal);
                        };

                        const btnEliminar = document.createElement('button');
                        btnEliminar.textContent = 'Eliminar';
                        btnEliminar.className = 'btn-eliminar';
                        btnEliminar.onclick = () => {
                            // Crear modal de confirmación para eliminar
                            let modal = document.createElement('div');
                            modal.style.position = 'fixed';
                            modal.style.top = '0';
                            modal.style.left = '0';
                            modal.style.width = '100vw';
                            modal.style.height = '100vh';
                            modal.style.background = 'rgba(0,0,0,0.5)';
                            modal.style.display = 'flex';
                            modal.style.alignItems = 'center';
                            modal.style.justifyContent = 'center';
                            modal.style.zIndex = '9999';

                            let modalContent = document.createElement('div');
                            modalContent.className = 'custom-modal';

                            let mensaje = document.createElement('p');
                            mensaje.textContent = '¿Seguro que deseas eliminar este registro?';
                            modalContent.appendChild(mensaje);
                            let detalle = document.createElement('p');
                            let label = '';
                            if (item.username) {
                                label = `Usuario: ${item.username}`;
                            } else if (item.title) {
                                label = `Título: ${item.title}`;
                            } else {
                                label = `${headers[0]}: ${item[headers[0]]}`;
                            }
                            detalle.textContent = label;
                            detalle.style.fontWeight = 'bold';
                            detalle.style.marginBottom = '10px';
                            modalContent.appendChild(detalle);

                            let btnConfirmar = document.createElement('button');
                            btnConfirmar.textContent = 'Eliminar';
                            btnConfirmar.className = 'btn-eliminar';
                            btnConfirmar.style.marginRight = '10px';
                            btnConfirmar.onclick = function() {
                                const formData = new FormData();
                                formData.append('id', item[headers[0]]);
                                fetch(`${PATH}${tabla}/delete`, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(() => {
                                    document.body.removeChild(modal);
                                    fetchAndRender(currentPage);
                                });
                            };

                            let btnCancelar = document.createElement('button');
                            btnCancelar.textContent = 'Cancelar';
                            btnCancelar.className = 'btn-cancelar';
                            btnCancelar.onclick = function() {
                                document.body.removeChild(modal);
                            };

                            modalContent.appendChild(btnConfirmar);
                            modalContent.appendChild(btnCancelar);
                            modal.appendChild(modalContent);
                            document.body.appendChild(modal);
                        };

                        tdAcciones.appendChild(btnEditar);
                        tdAcciones.appendChild(btnEliminar);
                        tr.appendChild(tdAcciones);

                        tbody.appendChild(tr);
                    });
                }

                // Inicializar filas
                renderRows(data);

                // Evento de búsqueda (filtra solo los datos de la página actual)
                searchInput.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    const filtered = data.filter(item =>
                        headers.some(header =>
                            String(item[header]).toLowerCase().includes(value)
                        )
                    );
                    renderRows(filtered);
                });

                table.appendChild(thead);
                table.appendChild(tbody);
                contenedor.appendChild(table);

                // Paginación
                const totalPages = Math.ceil(total / pageSize);
                if (totalPages > 1) {
                    const paginationDiv = document.createElement('div');
                    paginationDiv.className = 'pagination';
                    paginationDiv.style.marginTop = '10px';
                    paginationDiv.style.display = 'flex';
                    paginationDiv.style.gap = '5px';

                    const prevBtn = document.createElement('button');
                    prevBtn.textContent = 'Anterior';
                    prevBtn.disabled = page === 1;
                    prevBtn.onclick = () => {
                        if (currentPage > 1) {
                            currentPage--;
                            fetchAndRender(currentPage);
                        }
                    };
                    paginationDiv.appendChild(prevBtn);

                    // Números de página
                    for (let i = 1; i <= totalPages; i++) {
                        const pageBtn = document.createElement('button');
                        pageBtn.textContent = i;
                        if (i === page) pageBtn.disabled = true;
                        pageBtn.onclick = () => {
                            currentPage = i;
                            fetchAndRender(currentPage);
                        };
                        paginationDiv.appendChild(pageBtn);
                    }

                    const nextBtn = document.createElement('button');
                    nextBtn.textContent = 'Siguiente';
                    nextBtn.disabled = page === totalPages;
                    nextBtn.onclick = () => {
                        if (currentPage < totalPages) {
                            currentPage++;
                            fetchAndRender(currentPage);
                        }
                    };
                    paginationDiv.appendChild(nextBtn);

                    contenedor.appendChild(paginationDiv);
                    const infoDiv = document.createElement('div');
                    infoDiv.style.marginTop = '10px';
                    infoDiv.textContent = `Mostrando ${data.length} de ${total} resultados`;
                    contenedor.appendChild(infoDiv);
                }
            });
        }

        fetchAndRender(currentPage);
    }
});