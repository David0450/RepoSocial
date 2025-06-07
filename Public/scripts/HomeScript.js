let offset = 0;
const limit = 12;
let loading = false;
let noMorePosts = false;

let category = null;
let tags = [];
let order = null;

const repos_url = `${BASE_PATH}projects`;
const feed = document.getElementById('projects_list');

let botonesCategorias = document.getElementsByClassName('category_item');

// Añadir select para ordenar
const sortContainer = document.createElement('div');
sortContainer.className = 'sort_container';
const sortSection = document.createElement('div');
sortSection.className = 'sort_section';

const filterSection = document.createElement('div');
filterSection.className = 'filter_section';
const filterIcon = document.createElement('i');
filterIcon.className = 'fa-solid fa-filter filter-icon';
filterIcon.style.cursor = 'pointer';

const filterDropdown = document.createElement('div');
filterDropdown.className = 'filter-dropdown';

// Crea el contenedor de categorías
const sidebarCategories = document.createElement('div');
sidebarCategories.className = 'sidebar_categories';

const categoriesInnerDiv = document.createElement('div');

const categoriesHeader = document.createElement('div');
categoriesHeader.className = 'sidebar_categories_header';

const categoriesH1 = document.createElement('h1');
categoriesH1.textContent = 'Categorias';

categoriesHeader.appendChild(categoriesH1);

const mobileCategoriesList = document.createElement('div');
mobileCategoriesList.className = 'categories_list mobile';
mobileCategoriesList.id = 'mobile_categories_list';

categoriesInnerDiv.appendChild(categoriesHeader);
categoriesInnerDiv.appendChild(mobileCategoriesList);
sidebarCategories.appendChild(categoriesInnerDiv);

// Crea el contenedor de etiquetas
const sidebarTags = document.createElement('div');
sidebarTags.className = 'sidebar_tags';

const tagsInnerDiv = document.createElement('div');

const tagsHeader = document.createElement('div');
tagsHeader.className = 'sidebar_tags_header';

const tagsH1 = document.createElement('h1');
tagsH1.textContent = 'Etiquetas';

tagsHeader.appendChild(tagsH1);

const mobileTagsList = document.createElement('div');
mobileTagsList.className = 'tags_list';
mobileTagsList.id = 'mobile_tags_list';

tagsInnerDiv.appendChild(tagsHeader);
tagsInnerDiv.appendChild(mobileTagsList);
sidebarTags.appendChild(tagsInnerDiv);

// Añade ambos al sidebar o al contenedor deseado
// Por ejemplo, si tienes un <div id="sidebar"></div> en tu HTML:
filterDropdown.appendChild(sidebarCategories);
filterDropdown.appendChild(sidebarTags);

// Mostrar/ocultar dropdown al hacer click en el icono
filterIcon.addEventListener('click', function (e) {
	e.stopPropagation();
	filterDropdown.style.visibility = filterDropdown.style.visibility === 'hidden' ? 'visible' : 'hidden';
});

// Ocultar dropdown al hacer click fuera
document.addEventListener('click', function () {
	filterDropdown.style.visibility = 'hidden';
});

// Añadir icono y dropdown al filterSection
filterSection.style.position = 'relative';
filterSection.appendChild(filterIcon);
filterSection.appendChild(filterDropdown);

const sortTitle = document.createElement('span');
sortTitle.textContent = 'Ordenar por ';
sortTitle.classList.add('sort_title');

const sortSelect = document.createElement('select');
sortSelect.id = 'sort_select';

const options = [
	{ value: 'fecha', text: 'fecha' },
	{ value: 'likes', text: 'likes' },
	//{ value: 'comentarios', text: 'comentarios' }
];

options.forEach(opt => {
	const option = document.createElement('option');
	option.value = opt.value;
	option.textContent = opt.text;
	sortSelect.appendChild(option);
});

// Icono a la derecha del select
const sortIcon = document.createElement('i');
sortIcon.className = 'fa-solid fa-sort';
sortIcon.style.cursor = 'pointer';

let sortDirection = 'desc';

sortIcon.addEventListener('click', function () {
	if (sortIcon.classList.contains('fa-sort')) {
		sortIcon.classList.remove('fa-sort');
		sortIcon.classList.add('fa-sort-up');
		sortDirection = 'asc';
	} else if (sortIcon.classList.contains('fa-sort-up')) {
		sortIcon.classList.remove('fa-sort-up');
		sortIcon.classList.add('fa-sort-down');
		sortDirection = 'desc';
	} else if (sortIcon.classList.contains('fa-sort-down')) {
		sortIcon.classList.remove('fa-sort-down');
		sortIcon.classList.add('fa-sort-up');
		sortDirection = 'asc';
	}
	feed.innerHTML = '';
	offset = 0;
	noMorePosts = false;
	loadPosts();
});
sortSection.appendChild(sortTitle);
sortSection.appendChild(sortSelect);
sortSection.appendChild(sortIcon);

sortContainer.appendChild(sortSection);
sortContainer.appendChild(filterSection);

// Insertar el select antes de la lista de proyectos
const parent = feed.parentNode;
parent.insertBefore(sortContainer, feed);

sortSelect.addEventListener('change', function() {
	feed.innerHTML = '';
	offset = 0;
	order = sortSelect.value;
	noMorePosts = false;
	loadPosts();
});

function loadPosts() {
	const noProjectsEl = document.getElementById('noProjects');
	const loadingEl = document.getElementById('loading');
	noProjectsEl.style.display = 'none';

	let loadingTimeout;
	loadingEl.style.display = 'none';

	if (loading || noMorePosts) return;
	loading = true;

	loadingTimeout = setTimeout(() => {
		loadingEl.style.display = 'inline';
	}, 300);

	fetch(`${repos_url}`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			offset: offset,
			tags: tags,
			category: category,
			order: order,
			direction: sortDirection
		})
	})
		.then(res => res.json())
		.then(posts => {	
			noMorePosts = false;

			if (!posts || posts.length === 0) {
				noProjectsEl.style.display = 'inline';
			}

			posts.forEach(post => {
				const el = document.createElement('div');
				el.className = 'post';
				el.setAttribute('data-id', post.id);

				// User section
				const postUser = document.createElement('div');
				postUser.className = 'post_user';

				const userImgDiv = document.createElement('div');
				const userLink = document.createElement('a');
				userLink.href = `${BASE_PATH}@${post.username}`;
				const insideBox = document.createElement('div');
				insideBox.className = 'inside-box';
				const avatarImg = document.createElement('img');
				avatarImg.src = `${BASE_PATH}${post.avatar_url}`;
				insideBox.appendChild(avatarImg);
				userLink.appendChild(insideBox);
				userImgDiv.appendChild(userLink);

				const usernameLink = document.createElement('a');
				usernameLink.href = `${BASE_PATH}@${post.username}`;
				usernameLink.style.color = 'var(--dodger-blue)';
				const strongUser = document.createElement('strong');
				strongUser.textContent = `@${post.username}`;
				usernameLink.appendChild(strongUser);

				postUser.appendChild(userImgDiv);
				postUser.appendChild(usernameLink);

				// Title
				const titleLink = document.createElement('a');
				titleLink.href = post.html_url;
				titleLink.style.color = 'var(--ghost-white)';
				const h5 = document.createElement('h5');
				h5.style.textDecoration = 'underline';
				h5.textContent = post.title;
				titleLink.appendChild(h5);

				// Date
				const small = document.createElement('small');
				small.textContent = timeAgo(post.uploaded_at);

				// Description
				const desc = document.createElement('p');
				desc.className = 'post_description';
				if (post.description == null || post.description == '') {
					desc.textContent = 'Sin descripción';
				} else {
					desc.textContent = post.description.slice(0, 120) + (post.description.length > 120 ? '...' : '');
				}

				// Category
				const cat = document.createElement('p');
				const strongCat = document.createElement('strong');
				strongCat.textContent = 'Categoría:';
				cat.appendChild(strongCat);
				cat.appendChild(document.createTextNode(' ' + (post.category_name ?? 'Sin categoría')));

				// Tags container
				const tagsContainer = document.createElement('div');
				tagsContainer.id = 'post_tags_container';
				tagsContainer.className = 'tags_list';

				// Stats
				const stats = document.createElement('div');
				stats.className = 'post_stats';
				stats.style.marginTop = '8px';

				// Likes
				const likesDiv = document.createElement('div');
				likesDiv.title = 'Likes';
				const likeIcon = document.createElement('i');
				likeIcon.className = 'fa-solid fa-heart like-button' + (post.has_liked ? ' clicked' : '');
				const likeSpan = document.createElement('span');
				likeSpan.textContent = post.like_count ?? 0;
				likesDiv.appendChild(likeIcon);
				likesDiv.appendChild(likeSpan);

				// Comments
				const commentsDiv = document.createElement('div');
				commentsDiv.title = 'Comentarios';
				commentsDiv.style.marginLeft = '16px';
				const commentIcon = document.createElement('i');
				commentIcon.className = 'fa-solid fa-comments comment-button';
				const commentSpan = document.createElement('span');
				commentSpan.textContent = post.comment_count ?? 0;
				commentsDiv.appendChild(commentIcon);
				commentsDiv.appendChild(commentSpan);

				stats.appendChild(likesDiv);
				stats.appendChild(commentsDiv);

				// Append all to el
				el.appendChild(postUser);
				el.appendChild(titleLink);
				el.appendChild(small);
				el.appendChild(desc);
				el.appendChild(cat);
				el.appendChild(tagsContainer);
				el.appendChild(stats);

				function timeAgo(dateString) {
					const date = new Date(dateString.replace(" ", "T")); // Convertir a formato ISO
					const now = new Date();
					const seconds = Math.floor((now - date) / 1000);
				
					const intervals = [
						{ label: 'año', seconds: 31536000 },
						{ label: 'mes', seconds: 2592000 },
						{ label: 'día', seconds: 86400 },
						{ label: 'hora', seconds: 3600 },
						{ label: 'minuto', seconds: 60 },
						{ label: 'segundo', seconds: 1 }
					];
				
					for (const interval of intervals) {
						const count = Math.floor(seconds / interval.seconds);
						if (count > 0) {
							return `Subido hace ${count} ${interval.label}${count !== 1 ? 's' : ''}`;
						}
					}
				
					return 'justo ahora';
				}

				fetch(`${BASE_PATH}project/tags?project_id=${post.id}`)
				.then(res => res.json())
				.then(tags => {
					const tagsContainer = el.querySelector('#post_tags_container');
					tagsContainer.id = ''; 
					tags.forEach(tag => {
						const tagLink = document.createElement('a');
						tagLink.className = 'tag_item';
						const tagSpan = document.createElement('span');
						tagSpan.innerHTML = '<i class="fa-solid fa-tag"></i>'+tag;
						tagLink.appendChild(tagSpan);
						tagsContainer.appendChild(tagLink	);
					});
				})
				.catch(error => console.error('Error fetching tags:', error));

				feed.appendChild(el);
			});
		  
			offset += posts.length;
		})
		.finally(() => {
			loading = false;
			clearTimeout(loadingTimeout);
			loadingEl.style.display = 'none';

			const likeButtons = document.querySelectorAll('.like-button');
			
			likeButtons.forEach(button => {
				// Evitar añadir múltiples listeners
				if (button.dataset.listenerAdded) return;
				button.dataset.listenerAdded = "true";

				button.addEventListener('click', function() {
					const postElement = this.closest('.post');
					const projectId = postElement ? postElement.getAttribute('data-id') : null;
					
					const likeSpan = this.nextElementSibling;
					const likeText = likeSpan.textContent.trim();
					const match = likeText.match(/(\d+)$/);
					if (!match) return;
					const currentLikes = parseInt(match[1], 10);

					if (this.classList.contains('clicked')) {
						this.classList.remove('clicked');
						if (projectId) {
							fetch(`${BASE_PATH}projects/removeLike`, {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ project_id: projectId })
							})
							.then(res => res.json())
							.then(data => {
								if (data.status == 'success') {
									likeSpan.textContent = `${currentLikes - 1}`;
								}
							})
							.catch(error => {
								console.error('Error al eliminar like:', error);
							});
						}
					} else {
						this.classList.add('clicked');
						if (projectId) {
							fetch(`${BASE_PATH}projects/addLike`, {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ project_id: projectId })
							})
							.then(res => res.json())
							.then(data => {
								if (data.status == 'success') {
									likeSpan.textContent = `${currentLikes + 1}`;
								}
							})
							.catch(error => {
								console.error('Error al dar like:', error);
							});
						}
					}
				});
			});
		});
}

// Detectar scroll al fondo
window.addEventListener('scroll', () => {
  	if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
    	loadPosts();
  	}
});


const categoriesList = document.getElementById('categories_list');
categoriesList.addEventListener('click', function(event) {
	const target = event.target.closest('.category_item');
	if (!target) return;

	feed.innerHTML = '';

	if (target.classList.contains('active')) {
		target.classList.remove('active');
		category = null;
	} else {
		const oldActiveCategory = categoriesList.querySelector('.active');
		if (oldActiveCategory) {
			oldActiveCategory.classList.remove('active');
		}
		target.classList.add('active');
		category = target.getAttribute('value');
	}
	offset = 0;
	noMorePosts = false;
	loadPosts();
})

const tagsList = document.getElementById('tags_list');
tagsList.addEventListener('click', function(event) {
	const target = event.target.closest('.tag_item');
	if (!target) return;

	feed.innerHTML = '';

	if (target.classList.contains('active')) {
		target.classList.remove('active');
	} else {
		target.classList.add('active');
	}

	const tagValue = target.getAttribute('value');
	if (target.classList.contains('active')) {
		if (!tags.includes(tagValue)) {
			tags.push(tagValue);
		}
	} else {
		const index = tags.indexOf(tagValue);
		if (index !== -1) {
			tags.splice(index, 1);
		}
	}

	offset = 0;
	noMorePosts = false;
	loadPosts();
});

$.ajax({
    url: '?uri=categories',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
        // Populate the sidebar with categories
        var categoriesList = $('#mobile_categories_list');
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
        var categoriesList = $('#mobile_categories_list');
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
            var tagsList = $('#mobile_tags_list');
            $("#tags_list").css("display", "flex");
            tagsList.empty();
            tagsList.append('<p class="sidebar_error">No se han podido obtener etiquetas</p>');
            $(".sidebar_error").css("font-size", "1.2rem");
            $(".sidebar_error").css("color", "var(--ghost-white)");
            $(".sidebar_error").css("text-align", "center");
            $(".sidebar_error").css("font-weight", "bold");
            $(".sidebar_error").width("100%");
        } else {
            var tagsList = $('#mobile_tags_list');
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


loadPosts();