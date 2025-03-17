<?php

class EmptyModel {
    protected $db;
    protected $table;
    protected $primaryKey;

    public function __construct($table, $primaryKey = 'id') {
        $this->db = Database::getInstance()->getConnection();
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Obtener todos los registros
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un registro por clave primaria
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->query($sql, [$id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo registro
    public function create($data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }

    // Actualizar un registro por clave primaria
    public function update($data, $id) {
        $setClause = implode(', ', array_map(fn($field) => "{$field} = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        $this->query($sql, array_merge(array_values($data), [$id]));
    }

    // Eliminar un registro por clave primaria
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $this->query($sql, [$id]);
    }
}