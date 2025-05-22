<?php
namespace App\Controllers;

use App\Models\Chat;
use App\Controllers\MainController;
use PDO;

class ChatController extends MainController{
    protected $chatModel;

    public function __construct() {
        $this->chatModel = new Chat();
    }

    // HTTP: mostrar la vista del chat
    public function mostrarVistaChat() {
        //$_SESSION['usuario'] = $_SESSION['usuario'] ?? 'Usuario' . rand(1000, 9999);
        $usuario = $_SESSION['user']['username'];

        $userId = $this->chatModel->obtenerIdPorNombre($usuario);
        $chats = $this->chatModel->obtenerChatsDeUsuario($userId);

        $this->renderChat($chats, $userId);
    }

    // WebSocket: guardar mensaje enviado en chat
    public function guardarMensajeDirecto($chatId, $userId, $mensaje) {
        return $this->chatModel->guardarMensaje($chatId, $userId, $mensaje);
    }

    // WebSocket: verificar si usuario pertenece a chat
    public function usuarioPerteneceAlChat($nombreUsuario, $chatId) {
        return $this->chatModel->usuarioPerteneceAlChat($nombreUsuario, $chatId);
    }

    // WebSocket: obtener ID por nombre
    public function obtenerIdPorNombre($nombre) {
        return $this->chatModel->obtenerIdPorNombre($nombre);
    }

    // WebSocket (opcional): obtener nombres de los miembros de un chat
    public function obtenerNombresMiembrosChat($chatId) {
        return $this->chatModel->obtenerMiembrosDeChat($chatId);
    }
    public function obtenerMensajes() {
        
        if (!isset($_GET['chat_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'chat_id no especificado']);
            return;
        }

        $chatId = $_GET['chat_id'];

        
        header('Content-Type: application/json');
        echo json_encode($this->chatModel->obtenerMensajesDeChat($chatId));
        
    }

}
