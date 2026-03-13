<?php
class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->select($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }

    public function create($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ($placeholders)";
        return $this->db->insert($sql, $values);
    }

    public function update($id, $data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $values[] = $id;
        
        $setPart = implode(' = ?, ', $fields) . ' = ?';
        $sql = "UPDATE {$this->table} SET $setPart WHERE id = ?";
        return $this->db->update($sql, $values);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }
}
