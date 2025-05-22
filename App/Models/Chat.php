<?php
namespace App\Models;

use PDO;
use App\Core\EmptyModel;

class Chat extends EmptyModel{
    protected $db;

    public function __construct() {
        parent::__construct('chats');
    }   

    public function obtenerIdPorNombre($nombre) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetchColumn();
    }

    public function obtenerNombrePorId($id) {
        $stmt = $this->db->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function obtenerChatsDeUsuario($userId) {
        $stmt = $this->db->prepare("
            SELECT c.id AS chat_id,
                   GROUP_CONCAT(u.username SEPARATOR ', ') AS miembros
            FROM chats c
            JOIN chat_members m ON c.id = m.chat_id
            JOIN users u ON u.id = m.user_id
            WHERE c.id IN (
                SELECT chat_id FROM Chat_Members WHERE user_id = ?
            )
            GROUP BY c.id
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarMensaje($chatId, $userId, $mensaje) {
        $stmt = $this->db->prepare("
            INSERT INTO chat_messages (chat_id, user_id, body, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$chatId, $userId, $mensaje]);
    }

    public function obtenerMiembrosDeChat($chatId) {
        $stmt = $this->db->prepare("
            SELECT u.username
            FROM chat_members cm
            JOIN users u ON u.id = cm.user_id
            WHERE cm.chat_id = ?
        ");
        $stmt->execute([$chatId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function usuarioPerteneceAlChat($nombreUsuario, $chatId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM chat_members cm
            JOIN users u ON u.id = cm.user_id
            WHERE cm.chat_id = ? AND u.username = ?
        ");
        $stmt->execute([$chatId, $nombreUsuario]);
        return $stmt->fetchColumn() > 0;
    }

    public function obtenerMensajesDeChat($chatId) {
        $stmt = $this->db->prepare("
            SELECT u.username AS autor, m.body, m.created_at
            FROM chat_messages m
            JOIN users u ON u.id = m.user_id
            WHERE m.chat_id = ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$chatId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
