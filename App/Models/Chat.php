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
                   COUNT(cm.user_id) AS total_miembros
            FROM chats c
            JOIN chat_members cm ON c.id = cm.chat_id
            WHERE cm.chat_id IN (
                SELECT chat_id FROM chat_members WHERE user_id = ?
            )
            GROUP BY c.id
        ");
        $stmt->execute([$userId]);
        $chatsBase = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($chatsBase as $chat) {
            $chatId = $chat['chat_id'];
            $miembros = $this->obtenerMiembrosDeChatConAvatar($chatId);
            $ultimo = $this->obtenerUltimoMensajeDelChat($chatId);

            if (count($miembros) === 2) {
                foreach ($miembros as $m) {
                    if ($m['username'] !== $this->obtenerNombrePorId($userId)) {
                        $resultado[] = [
                            'chat_id' => $chatId,
                            'nombre' => $m['username'],
                            'avatar_url' => $m['avatar_url'],
                            'es_privado' => true,
                            'ultimo_mensaje' => $ultimo
                        ];
                    }
                }
            } else {
                // Chat grupal
                $nombres = array_column($miembros, 'username');
                $resultado[] = [
                    'chat_id' => $chatId,
                    'nombre' => implode(', ', $nombres),
                    'avatar_url' => null,
                    'es_privado' => false,
                    'ultimo_mensaje' => $ultimo
                ];
            }
        }

        return $resultado;
    }

    public function obtenerUltimoMensajeDelChat($chatId) {
    $stmt = $this->db->prepare("
            SELECT m.body, u.username AS autor, m.created_at
            FROM chat_messages m
            JOIN users u ON u.id = m.user_id
            WHERE m.chat_id = ?
            ORDER BY m.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$chatId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
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

    public function obtenerMensajesDeChat($chatId, $limite = 20, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT * FROM (
                SELECT u.username AS autor, m.body, m.created_at
                FROM chat_messages m
                JOIN users u ON u.id = m.user_id
                WHERE m.chat_id = ?
                ORDER BY m.created_at DESC
                LIMIT ? OFFSET ?
            ) sub
            ORDER BY sub.created_at ASC
        ");
        $stmt->bindValue(1, $chatId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMiembrosDeChatConAvatar($chatId) {
        $stmt = $this->db->prepare("
            SELECT u.username, u.avatar_url
            FROM chat_members cm
            JOIN users u ON u.id = cm.user_id
            WHERE cm.chat_id = ?
        ");
        $stmt->execute([$chatId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearChatConUsuario($userId1, $userId2) {
        // Verificar si ya existe un chat privado entre ambos
        $sql = "
            SELECT c.id
            FROM chats c
            JOIN chat_members m1 ON c.id = m1.chat_id AND m1.user_id = ?
            JOIN chat_members m2 ON c.id = m2.chat_id AND m2.user_id = ?
            GROUP BY c.id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId1, $userId2]);
        $chatIdExistente = $stmt->fetchColumn();

        if ($chatIdExistente) {
            return $chatIdExistente;
        }

        // Crear nuevo chat y agregar miembros
        $this->db->beginTransaction();

        $this->db->exec("INSERT INTO chats (created_at) VALUES (NOW())");
        $chatId = $this->db->lastInsertId();

        $stmt = $this->db->prepare("INSERT INTO chat_members (chat_id, user_id, joined_at) VALUES (?, ?, NOW())");
        $stmt->execute([$chatId, $userId1]);
        $stmt->execute([$chatId, $userId2]);

        $this->db->commit();

        return $chatId;
    }
}
