<?php
class Dish extends Model {
    protected $table = 'dishes';

    public function getDishesByHotel($hotelId) {
        $sql = "SELECT d.*, c.name as category_name 
                FROM {$this->table} d
                LEFT JOIN menu_categories c ON d.category_id = c.id
                WHERE d.hotel_id = ? AND d.is_active = 1 
                ORDER BY c.display_order, d.name";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getDishesByCategory($hotelId, $categoryId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE hotel_id = ? AND category_id = ? AND is_active = 1 
                ORDER BY name";
        return $this->db->select($sql, [$hotelId, $categoryId]);
    }

    public function getAvailableDishes($hotelId, $serviceTime = null) {
        $sql = "SELECT d.*, c.name as category_name 
                FROM {$this->table} d
                LEFT JOIN menu_categories c ON d.category_id = c.id
                WHERE d.hotel_id = ? AND d.is_active = 1 AND d.is_available = 1";
        $params = [$hotelId];
        
        if ($serviceTime) {
            $sql .= " AND (d.service_time = ? OR d.service_time = 'all_day')";
            $params[] = $serviceTime;
        }
        
        $sql .= " ORDER BY c.display_order, d.name";
        return $this->db->select($sql, $params);
    }

    public function toggleAvailability($dishId) {
        $sql = "UPDATE {$this->table} SET is_available = NOT is_available WHERE id = ?";
        return $this->db->update($sql, [$dishId]);
    }
}
