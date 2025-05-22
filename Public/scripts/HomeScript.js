let offset = 0;
const limit = 12;
let loading = false;
let noMorePosts = false;

const repos_url = `${BASE_PATH}projects`;

let botonesCategorias = document.getElementsByClassName('category_item');

function loadPosts() {

  	if (loading || noMorePosts) return;
  	loading = true;
  	document.getElementById('loading').style.display = 'block';

	const feed = document.getElementById('projects_list');
	while (feed.firstChild) {
		feed.removeChild(feed.firstChild);
	}
  	fetch(`${repos_url}?offset=${offset}`)
  	  	.then(res => res.json())
  	  	.then(posts => {
  	  	  	if (posts.length === 0) {
  	  	  		noMorePosts = true;
  	  	  		document.getElementById('loading').innerText = 'No hay más posts.';
  	  	  		return;
  	  	  	}
			noMorePosts = false;
  	  	  	posts.forEach(post => {
  	  	  	  	const el = document.createElement('div');
				el.className = 'post';
				el.innerHTML = `
					<div class="post_user">
						<div>
							<a href="${BASE_PATH}@${post.username}/profile">
								<div class="inside-box">									
									<img src='${BASE_PATH}${post.avatar_url}'>
								</div>
							</a>
						</div>
						<a href="${BASE_PATH}@${post.username}/profile" style="color: var(--dodger-blue);">
							<strong>@${post.username}</strong>
						</a>
					</div>
					<a href="${post.html_url}" style="color: var(--ghost-white);">
						<h5 style="text-decoration: underline;">${post.title}</h5>
					</a>
					<small>${timeAgo(post.uploaded_at)}</small>
					<p>${post.description ?? 'Sin descripción'}</p>
					<p><strong>Categoría:</strong> ${post.category_name ?? 'Sin categoría'}</p>
					<div id='post_tags_container' class='tags_list'>

					</div>
					<hr>
				`;

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
							return `hace ${count} ${interval.label}${count !== 1 ? 's' : ''}`;
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

				/*fetch(`https://api.github.com/repos/${username}/${repoName}/git/trees/${branch}?recursive=1`)
				.then(response => response.json())
				.then(data => {
				  if (!data.tree) {
					console.error("No se pudo obtener el árbol del repositorio");
					return;
				  }
				
				  // Convertimos la lista plana a un objeto anidado
				  const tree = {};
				
				  data.tree.forEach(item => {
					const parts = item.path.split("/");
					let current = tree;
				
					parts.forEach((part, index) => {
					  const isFile = item.type === "blob" && index === parts.length - 1;
				
					  if (!current[part]) {
						current[part] = isFile ? null : {};
					  }
				
					  if (!isFile) {
						current = current[part];
					  }
					});
				  });
				
				  // Función recursiva para imprimir el árbol
				  function printTree(node, indent = "") {
					const entries = Object.entries(node).sort(([a], [b]) => a.localeCompare(b));
				
					for (const [name, child] of entries) {
					  const isFile = child === null;
					  if (isFile) {
						console.log(`${indent}📄 ${name}`);
					  } else {
						console.log(`${indent}📁 ${name}`);
						printTree(child, indent + "  ");
					  }
					}
				  }
				
				  console.log(`📦 ${repoName}`);
				  printTree(tree, "  ");
				})
				.catch(error => console.error("Error:", error));*/
  	  	  	});
		  
  	  	  	offset += posts.length;
  	  	})
  	  	.finally(() => {
  	  	  	loading = false;
  	  	  	if (!noMorePosts) document.getElementById('loading').style.display = 'none';
  	  	});
}

// Detectar scroll al fondo
window.addEventListener('scroll', () => {
  	if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
    	loadPosts();
  	}
});

// Cargar los primeros posts
loadPosts();

const categoriesList = document.getElementById('categories_list');

categoriesList.addEventListener('click', function(event) {
	const target = event.target.closest('.category_item');
	if (target) {
		const feed = document.getElementById('projects_list');
		while (feed.firstChild) {
			feed.removeChild(feed.firstChild);
		}
		fetch(`${BASE_PATH}projects/:${target.getAttribute('value')}`)
		  	.then(res => res.json())
			.then(posts => {
				console.log(posts);
				if (posts.length === 0) {
  	  	  			noMorePosts = true;
  	  	  			document.getElementById('loading').innerText = 'No hay más posts.';
  	  	  			return;
  	  	  		}
				noMorePosts = false;
		  
  	  	  		posts.forEach(post => {
					const repoName = post.title;
					const username = post.username;
					const branch = 'main';
  	  	  		  	const el = document.createElement('div');
					el.className = 'post';
					el.innerHTML = `
						<div class="post_user">
							<div>
								<a href="${BASE_PATH}@${post.username}/profile">
									<div class="inside-box">									
										<img src='${BASE_PATH}${post.avatar_url}'>
									</div>
								</a>
							</div>
							<a href="${BASE_PATH}@${post.username}/profile" style="color: var(--dodger-blue);">
								<strong>@${post.username}</strong>
							</a>
						</div>
						<h5>${post.title}</h5>
						<small>${timeAgo(post.uploaded_at)}</small>
						<p>${post.description ?? 'Sin descripción'}</p>
						<p><strong>Categoría:</strong> ${post.category_name ?? 'Sin categoría'}</p>
						<div id='post_tags_container' class='tags_list'>

						</div>
					`;

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
								return `hace ${count} ${interval.label}${count !== 1 ? 's' : ''}`;
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
  	  	  		if (!noMorePosts) {
					document.getElementById('loading').style.display = 'none'
				} else {
					document.getElementById('loading').style.display = 'block'
				};
  	  		});
	};
});

const tagsList = document.getElementById('tags_list');

tagsList.addEventListener('click', function(event) {
	const target = event.target.closest('.tag_item');
	if (target) {
		const feed = document.getElementById('projects_list');
		while (feed.firstChild) {
			feed.removeChild(feed.firstChild);
		}
		fetch(`${BASE_PATH}projects/;${target.getAttribute('value')}`)
		  	.then(res => res.json())
			.then(posts => {
				console.log(posts);
				if (posts.length === 0) {
  	  	  			noMorePosts = true;
  	  	  			document.getElementById('loading').innerText = 'No hay más posts.';
  	  	  			return;
  	  	  		}
				noMorePosts = false;
		  
  	  	  		posts.forEach(post => {
					const repoName = post.title;
					const username = post.username;
					const branch = 'main';
  	  	  		  	const el = document.createElement('div');
					el.className = 'post';
					el.innerHTML = `
						<div class="post_user">
							<div>
								<a href="${BASE_PATH}@${post.username}/profile">
									<div class="inside-box">									
										<img src='${BASE_PATH}${post.avatar_url}'>
									</div>
								</a>
							</div>
							<a href="${BASE_PATH}@${post.username}/profile" style="color: var(--dodger-blue);">
								<strong>@${post.username}</strong>
							</a>
						</div>
						<h5>${post.title}</h5>
						<small>${timeAgo(post.uploaded_at)}</small>
						<p>${post.description ?? 'Sin descripción'}</p>
						<p><strong>Categoría:</strong> ${post.category_name ?? 'Sin categoría'}</p>
						<div id='post_tags_container' class='tags_list'>

						</div>
						<hr>
					`;

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
								return `hace ${count} ${interval.label}${count !== 1 ? 's' : ''}`;
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
  	  	  		if (!noMorePosts) {
					document.getElementById('loading').style.display = 'none'
				} else {
					document.getElementById('loading').style.display = 'block'
				};
  	  		});
	};
});
