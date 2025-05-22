<?php
namespace App\Core;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Controllers\ChatController;
use PDO;

class ChatSocket implements MessageComponentInterface {
    protected $clients;
    protected $userConnections = [];
    protected $userIds = [];
    protected $chatController;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        $this->chatController = new ChatController(); // <-- se le pasa $db
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nueva conexión: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        echo "Mensaje recibido: $msg\n";
        if ($data['type'] === 'identificar') {
            $username = $data['user'];
            $userId = $this->chatController->obtenerIdPorNombre($username); // deberías crear este método

            if (!$userId) {
                echo "Usuario no encontrado\n";
                return;
            }

            $from->username = $username;
            $this->userConnections[$username] = $from;
            $this->userIds[$username] = $userId;

            $this->broadcastUserList();
        }

        if ($data['type'] === 'mensaje_chat') {
            $chatId = $data['chat_id'];
            $texto = $data['text'];
            $userId = $this->userIds[$from->username] ?? null;

            if ($chatId && $userId && $texto) {
                $this->chatController->guardarMensajeDirecto($chatId, $userId, $texto); // método personalizado

                // reenviar mensaje a los usuarios conectados del chat
                foreach ($this->userConnections as $nombre => $cliente) {
                    if ($this->chatController->usuarioPerteneceAlChat($nombre, $chatId)) {
                        $cliente->send(json_encode([
                            'type' => 'mensaje',
                            'chat_id' => $chatId,
                            'from' => $from->username,
                            'text' => $texto
                        ]));
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        if (isset($conn->username)) {
            unset($this->userConnections[$conn->username]);
            unset($this->userIds[$conn->username]);
            $this->broadcastUserList();
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function broadcastUserList() {
        $usernames = array_keys($this->userConnections);
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'type' => 'usuarios',
                'usuarios' => $usernames
            ]));
        }
    }
}
