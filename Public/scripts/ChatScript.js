const conn = new WebSocket('ws://localhost:8080');
let chatIdActivo = null;

conn.onopen = function() {
    conn.send(JSON.stringify({
        type: "identificar",
        user: username
    }));
};

document.querySelectorAll('#listaChats li').forEach(li => {
    li.addEventListener('click', () => {
        chatIdActivo = li.dataset.chatId;
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
    
        fetch(`${PATH}chats/_${chatIdActivo}/messages`)
            .then(res => res.json())
            .then(mensajes => {
                mensajes.forEach(m => {
                    const message = document.createElement('li');
                    const div = document.createElement('div');
                    const span = document.createElement('span');

                    if (m.autor === username) {
                        span.classList.add('self');
                    }
                    // Calcular "hace cuanto" en minutos/horas/días
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
                    span.innerHTML = `<strong>${m.autor}:</strong> ${m.body} <em>(${tiempo})</em>`;
                    message.appendChild(span);
                    message.appendChild(div);
                    document.getElementById('messages').appendChild(message);
                });
            })
            .then(() => {

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

                const messagesContainer = document.getElementById('messages');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
    });
});

conn.onmessage = function(e) {
    const data = JSON.parse(e.data);
    if (data.type === "mensaje" && data.chat_id === chatIdActivo) {
        const message = document.createElement('li');
        const div = document.createElement('div');
        const span = document.createElement('span');

        if (data.from === username) {
            span.classList.add('self');
        }
        // Calcular "hace cuanto" en segundos/minutos/horas/días
        const fecha = data.created_at ? new Date(data.created_at) : new Date();
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
        li.innerHTML = `<strong>${data.from}:</strong> ${data.text} <em>(${tiempo})</em>`;
        message.appendChild(span);
        message.appendChild(div);
        document.getElementById('messages').appendChild(message);
    }
};

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