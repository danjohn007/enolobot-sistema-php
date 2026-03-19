<?php
class Wine extends Model {
    protected $table = 'wines';

    public function getWinesByHotel($hotelId) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1 ORDER BY display_order ASC, name ASC";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getAllWines() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY hotel_id ASC, display_order ASC, name ASC";
        return $this->db->select($sql, []);
    }
}
