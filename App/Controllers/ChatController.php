<?php
namespace App\Controllers;

use App\Models\Chat;
use App\Controllers\MainController;
use App\Core\Config;
use PDO;

class ChatController extends MainController{
    protected $chatModel;

    public function __construct() {
        $this->chatModel = new Chat();
    }

    // HTTP: mostrar la vista del chat
    public function mostrarVistaChat() {

        $this->renderChat();
    }

    public function obtenerListaChatsJson() {
        $usuario = $_SESSION['user']['username'];
        $userId = $this->chatModel->obtenerIdPorNombre($usuario);

        header('Content-Type: application/json');
        echo json_encode($this->chatModel->obtenerChatsDeUsuario($userId));
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

        $chatId = (int)$_GET['chat_id'];
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $limite = 20;

        header('Content-Type: application/json');
        echo json_encode($this->chatModel->obtenerMensajesDeChat($chatId, $limite, $offset));
    }

    public function obtenerAvatarDelOtroMiembro($chatId, $usuarioActual) {
        $miembros = $this->chatModel->obtenerMiembrosDeChatConAvatar($chatId);

        // Si es un chat 1 a 1
        if (count($miembros) === 2) {
            foreach ($miembros as $m) {
                if ($m['nombre'] !== $usuarioActual) {
                    return $m['avatar_url'];
                }
            }
        }
        return null;
    }

    public function crearChatConUsuario() {
        if (!isset($_GET['parametro'])) {
            http_response_code(400);
            echo json_encode(['error' => 'username no especificado']);
            return;
        }

        $username = $_GET['parametro'];
        $userId = $this->chatModel->obtenerIdPorNombre($username);

        if (!$userId) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        $chatId = $this->chatModel->crearChatConUsuario($userId, $_SESSION['user']['id']);

        if (!$chatId) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el chat']);
            return;
        }

        // Redirigir a la vista del chat
        header('Location: '.Config::PATH.'chats');
    }
}
