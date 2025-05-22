<?php

class Chat extends EmptyModel{
    public function __construct() {
        parent::__construct('chats');
    }
    
    public function guardarMensajePrivado($emisorId, $receptorId, $mensaje) {
        // 1. Buscar un chat existente entre los dos usuarios
        $sql = "
            SELECT c.id FROM Chat c
            JOIN Chat_Members m1 ON c.id = m1.chat_id AND m1.user_id = ?
            JOIN Chat_Members m2 ON c.id = m2.chat_id AND m2.user_id = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$emisorId, $receptorId]);
        $chatId = $stmt->fetchColumn();
    
        // 2. Si no existe, crear nuevo chat y miembros
        if (!$chatId) {
            $this->db->beginTransaction();
        
            $this->db->exec("INSERT INTO Chat (created_at) VALUES (NOW())");
            $chatId = $this->db->lastInsertId();
        
            $stmt = $this->db->prepare("INSERT INTO Chat_Members (chat_id, user_id, joined_at) VALUES (?, ?, NOW())");
            $stmt->execute([$chatId, $emisorId]);
            $stmt->execute([$chatId, $receptorId]);
        
            $this->db->commit();
        }
    
        // 3. Insertar el mensaje
        $stmt = $this->db->prepare("
            INSERT INTO Chat_Messages (chat_id, user_id, body, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$chatId, $emisorId, $mensaje]);
    }

}