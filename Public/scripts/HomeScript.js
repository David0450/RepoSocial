let offset = 0;
const limit = 10;
let loading = false;
let noMorePosts = false;

const repos_url = `${BASE_PATH}projects`;

function loadPosts() {
  	if (loading || noMorePosts) return;
  	loading = true;
  	document.getElementById('loading').style.display = 'block';

  	fetch(`${repos_url}?offset=${offset}`)
  	  	.then(res => res.json())
  	  	.then(posts => {
  	  	  	if (posts.length === 0) {
  	  	  		noMorePosts = true;
  	  	  		document.getElementById('loading').innerText = 'No hay más posts.';
  	  	  		return;
  	  	  	}
		  
  	  	  	const feed = document.getElementById('projects_list');
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
  	  	  	  	feed.appendChild(el);

				fetch(`https://api.github.com/repos/${username}/${repoName}/git/trees/${branch}?recursive=1`)
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
				.catch(error => console.error("Error:", error));
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