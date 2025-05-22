<!DOCTYPE html>
<html>
<head>
    <title>Chat WebSocket</title>
</head>
<body>
    <?php session_start(); ?>
    <h1>Chat</h1>
    <select id="destinatario"></select>
    <input id="msg" type="text">
    <button onclick="sendMessage()">Enviar</button>
    <ul id="messages"></ul>

    <?= $_SESSION['user']['username']?>

    <script>
        const username = "<?= $_SESSION['user']['username'] ?? 'anonimo' ?>";
        const conn = new WebSocket('ws://localhost:8080');
        
        conn.onopen = () => {
            console.log(username);
            conn.send(JSON.stringify({
                type: "identificar",
                user: username
            }));
        };

        conn.onmessage = function(e) {
            const data = JSON.parse(e.data);
        
            if (data.type === "usuarios") {
                const select = document.getElementById('destinatario');
                select.innerHTML = '';
            
                data.usuarios.forEach(user => {
                    if (user !== username) { // no incluirte a ti mismo
                        const option = document.createElement('option');
                        option.value = user;
                        option.textContent = user;
                        select.appendChild(option);
                    }
                });
            }
        
            if (data.type === "mensaje") {
                const li = document.createElement('li');
                li.innerHTML = `<strong>${data.from}:</strong> ${data.text}`;
                document.getElementById('messages').appendChild(li);
            }
        };

        function sendMessage() {
            const to = document.getElementById('destinatario').value;
            const text = document.getElementById('msg').value;
        
            conn.send(JSON.stringify({
                type: "privado",
                to: to,
                text: text
            }));
        
            document.getElementById('msg').value = '';
        }

    </script>
</body>
</html>
