<?php
namespace App\Core;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Controllers\ChatController;


class ChatSocket implements MessageComponentInterface {
    protected $clients;
    protected $userConnections = [];
    protected $chatController;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        $this->chatController = new ChatController();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
            echo "Nueva coneasxión: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
            echo "Mensaje recibido: $msg\n";
        $data = json_decode($msg, true);

        // Identificación de usuario
        if ($data['type'] === 'identificar') {
            $username = $data['user'];
            $this->userConnections[$username] = $from;
            $from->username = $username;

            $this->broadcastUserList();
            return;
        }

        // Envío de mensaje privado
        if ($data['type'] === 'privado') {
            $to = $data['to'];
            $text = $data['text'];
            $fromUser = $from->username ?? 'anónimo';

            if (isset($this->userConnections[$to])) {
                $this->userConnections[$to]->send(json_encode([
                    'type' => 'mensaje',
                    'from' => $fromUser,
                    'text' => $text
                ]));
            }
            return;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        // Eliminar usuario desconectado
        if (isset($conn->username)) {
            unset($this->userConnections[$conn->username]);
            $this->broadcastUserList();
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    // Envía la lista actual de usuarios conectados a todos
    protected function broadcastUserList() {
        $usernames = array_keys($this->userConnections);
        echo "Usuarios conectados: " . implode(', ', $usernames) . "\n"; // debug
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'type' => 'usuarios',
                'usuarios' => $usernames
            ]));
        }
    }

}
