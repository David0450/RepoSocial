const conn = new WebSocket('ws://localhost:8080');
let chatIdActivo = null;
let offset = 0;
const limit = 20;
const messagesContainer = document.getElementById('messages');
let cargando = false;

document.addEventListener('DOMContentLoaded', () => {
    fetch(`${PATH}getChats`)
    .then(res => res.json())
    .then(chats => {
        const lista = document.getElementById('listaChats');
        lista.innerHTML = '';

        chats.forEach(chat => {
            const li = document.createElement('li');
            li.dataset.chatId = chat.chat_id;
            li.dataset.chatName = chat.nombre;

            const avatarContainer = document.createElement('div');
            avatarContainer.classList.add('avatar-container');
            const avatar = document.createElement('img');
            avatar.classList.add('avatar-img');
            avatar.src = chat.avatar_url || `${PATH}default-avatar.png`;
            avatar.alt = 'avatar';

            avatarContainer.appendChild(avatar);

            const preview = document.createElement('div');
            preview.classList.add('chat-preview');

            const nombre = document.createElement('span');
            nombre.classList.add('chat-title');
            nombre.textContent = '@' + chat.nombre;

            if (chat.ultimo_mensaje) {
                const msg = chat.ultimo_mensaje;

                preview.innerHTML = `<strong>${msg.autor}:</strong> ${msg.body}`;
            } else {
                preview.textContent = 'No hay mensajes aún';
            }

            const container = document.createElement('div');
            container.classList.add('chat-item-content');
            container.appendChild(nombre);
            container.appendChild(preview);

            li.appendChild(avatarContainer);
            li.appendChild(container);
            lista.appendChild(li);
        });

        document.querySelectorAll('#listaChats li').forEach(li => {
            li.addEventListener('click', () => {
                console.log('Chat seleccionado:', li.dataset.chatId);
                chatIdActivo = li.dataset.chatId;
                offset = 0;
                document.getElementById('messages').innerHTML = '';
                const chatHeader = document.getElementById('chatWindowHeader');
                chatHeader.innerHTML = '';
                const soloUnMiembro = /^\w+$/.test(li.dataset.chatName);

                let chatName;
                if (soloUnMiembro) {
                    chatName = document.createElement('a');
                    chatName.classList.add('chatName');
                    chatName.href = `${PATH}@${li.dataset.chatName}/profile`;
                } else {
                    chatName = document.createElement('span');
                    chatName.classList.add('chatName');
                }
                chatName.innerText =  '@'+li.dataset.chatName;
                chatHeader.appendChild(chatName);
            
                cargarMensajes(0, true, false); // carga los primeros 20
            
                if (!document.querySelector('.chat_input')) {
                    const div = document.createElement('div');
                    div.classList.add('chat_input');
                
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.id = 'messageInput';
                    input.placeholder = 'Escribe un mensaje...';
                
                    const button = document.createElement('button');
                    button.onclick = enviarMensaje;
                    button.id = 'sendMessageButton';
                    button.innerText = 'Enviar';
                
                    div.appendChild(input);
                    div.appendChild(button);
                    document.getElementById('messages').parentNode.appendChild(div);
                }
            });
        });
    });
});

conn.onopen = function() {
    conn.send(JSON.stringify({
        type: "identificar",
        user: username
    }));
};

function cargarMensajes(offsetInicial = 0, append = true, alInicio = false) {
    if (!chatIdActivo || cargando) return;
    cargando = true;

    fetch(`${PATH}chats/_${chatIdActivo}/messages?offset=${offsetInicial}&limit=${limit}`)
        .then(res => res.json())
        .then(mensajes => {
            if (mensajes.length === 0) {
                cargando = false;
                return;
            }

            const prevScrollHeight = messagesContainer.scrollHeight;

            mensajes.forEach(m => {
                const li = document.createElement('li');
                const message = document.createElement('div');
                message.classList.add('message');
                
                const div = document.createElement('div');
                
                const messageContainer = document.createElement('div');
                messageContainer.classList.add('message-container');

                const messageText = document.createElement('span');
                messageText.classList.add('message-text');
                messageText.textContent = m.body;

                const autorName = document.createElement('span');
                autorName.classList.add('autor-name');
                autorName.textContent = m.autor;

                messageContainer.appendChild(autorName);
                messageContainer.appendChild(messageText);

                if (m.autor === username) {
                    messageContainer.classList.add('self');
                }

                const fecha = new Date(m.created_at);
                const ahora = new Date();
                const diffMs = ahora - fecha;
                const diffSeg = Math.floor(diffMs / 1000);
                let tiempo;
                if (diffSeg < 60) {
                    tiempo = `hace ${diffSeg} segundos`;
                } else if (diffSeg < 3600) {
                    tiempo = `hace ${Math.floor(diffSeg / 60)} minutos`;
                } else if (diffSeg < 86400) {
                    tiempo = `hace ${Math.floor(diffSeg / 3600)} horas`;
                } else {
                    tiempo = `hace ${Math.floor(diffSeg / 86400)} días`;
                }

                message.appendChild(messageContainer);
                message.appendChild(div);
                li.appendChild(message);

                if (alInicio) {
                    messagesContainer.prepend(li);
                } else {
                    messagesContainer.appendChild(li);
                }
            });

            if (alInicio) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight - prevScrollHeight;
            } else {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            offset += mensajes.length;
            cargando = false;
        });
}

messagesContainer.addEventListener('scroll', () => {
    if (messagesContainer.scrollTop === 0) {
        cargarMensajes(offset, true, true);
    }
});

conn.onmessage = function(e) {
    cargarMensajes(0, true, false);
}

function enviarMensaje() {
    const texto = document.getElementById('messageInput').value;
    if (!chatIdActivo) {
        alert("Selecciona un chat primero");
        return;
    }
    conn.send(JSON.stringify({
        type: "mensaje_chat",
        chat_id: chatIdActivo,
        text: texto
    }));

    document.getElementById('messageInput').value = '';
}
