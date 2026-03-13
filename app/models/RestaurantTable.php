<?php
class RestaurantTable extends Model {
    protected $table = 'restaurant_tables';

    public function getTablesByHotel($hotelId) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1 ORDER BY table_number";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getAvailableTables($hotelId, $date, $time) {
        $sql = "SELECT t.* FROM {$this->table} t 
                WHERE t.hotel_id = ? 
                AND t.status = 'available' 
                AND t.is_active = 1
                AND t.id NOT IN (
                    SELECT table_id FROM table_reservations 
                    WHERE hotel_id = ?
                    AND reservation_date = ?
                    AND reservation_time = ?
                    AND status NOT IN ('cancelled', 'completed')
                )
                ORDER BY t.table_number";
        return $this->db->select($sql, [$hotelId, $hotelId, $date, $time]);
    }

    public function updateStatus($tableId, $status) {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->db->update($sql, [$status, $tableId]);
    }
}
