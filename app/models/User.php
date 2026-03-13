<?php
class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->db->selectOne($sql, [$email]);
    }

    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $this->updateLastLogin($user['id']);
            return $user;
        }
        return false;
    }

    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }

    public function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        return $this->db->update($sql, [$userId]);
    }

    public function getUsersByHotel($hotelId, $role = null) {
        if ($role) {
            $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND role = ? AND is_active = 1";
            return $this->db->select($sql, [$hotelId, $role]);
        }
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getCollaborators($hotelId) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND role IN ('collaborator', 'hostess', 'restaurant_manager') AND is_active = 1";
        return $this->db->select($sql, [$hotelId]);
    }
}
