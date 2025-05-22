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
    
        fetch(`${PATH}chats/_${chatIdActivo}/messages`)
            .then(res => res.json())
            .then(mensajes => {
                mensajes.forEach(m => {
                    const li = document.createElement('li');
                    console.log(m.autor+" "+username);
                    if (m.autor === username) {
                        li.classList.add('self');
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
                    li.innerHTML = `<strong>${m.autor}:</strong> ${m.body} <em>(${tiempo})</em>`;
                    document.getElementById('messages').appendChild(li);
                });
            });
    });
});

conn.onmessage = function(e) {
    const data = JSON.parse(e.data);
    if (data.type === "mensaje" && data.chat_id === chatIdActivo) {
        const li = document.createElement('li');
        if (data.from === username) {
            li.classList.add('self');
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
        document.getElementById('messages').appendChild(li);
    }
};

function enviarMensaje() {
    const texto = document.getElementById('msg').value;
    if (!chatIdActivo) {
        alert("Selecciona un chat primero");
        return;
    }
    conn.send(JSON.stringify({
        type: "mensaje_chat",
        chat_id: chatIdActivo,
        text: texto
    }));

    document.getElementById('msg').value = '';
}