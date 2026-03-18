<?php
class Dish extends Model {
    protected $table = 'dishes';

    public function getDishesByHotel($hotelId) {
        // Detect schema: old DB uses category VARCHAR; new DB uses category_id FK
        $hasCategoryId = !empty($this->db->select(
            "SHOW COLUMNS FROM {$this->table} LIKE 'category_id'"
        ));
        $hasIsActive = !empty($this->db->select(
            "SHOW COLUMNS FROM {$this->table} LIKE 'is_active'"
        ));

        if ($hasCategoryId) {
            $isActiveClause = $hasIsActive ? "AND d.is_active = 1" : "";
            $sql = "SELECT d.*, COALESCE(c.name, d.category) as category_name
                    FROM {$this->table} d
                    LEFT JOIN menu_categories c ON d.category_id = c.id
                    WHERE d.hotel_id = ? {$isActiveClause}
                    ORDER BY c.display_order, d.name";
        } else {
            // Old schema: category is a plain VARCHAR column
            $sql = "SELECT *, category as category_name
                    FROM {$this->table}
                    WHERE hotel_id = ?
                    ORDER BY category, name";
        }

        return $this->db->select($sql, [$hotelId]);
    }

    public function getDishesByCategory($hotelId, $categoryId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE hotel_id = ? AND category_id = ? AND is_active = 1 
                ORDER BY name";
        return $this->db->select($sql, [$hotelId, $categoryId]);
    }

    public function getAvailableDishes($hotelId, $serviceTime = null) {
        // Detect schema
        $hasCategoryId = !empty($this->db->select(
            "SHOW COLUMNS FROM {$this->table} LIKE 'category_id'"
        ));
        $hasIsActive = !empty($this->db->select(
            "SHOW COLUMNS FROM {$this->table} LIKE 'is_active'"
        ));

        if ($hasCategoryId) {
            $isActiveClause = $hasIsActive ? "AND d.is_active = 1" : "";
            $sql = "SELECT d.*, COALESCE(c.name, d.category) as category_name
                    FROM {$this->table} d
                    LEFT JOIN menu_categories c ON d.category_id = c.id
                    WHERE d.hotel_id = ? {$isActiveClause} AND d.is_available = 1";
            $params = [$hotelId];

            if ($serviceTime) {
                $sql .= " AND (d.service_time = ? OR d.service_time = 'all_day')";
                $params[] = $serviceTime;
            }

            $sql .= " ORDER BY c.display_order, d.name";
        } else {
            // Old schema
            $sql = "SELECT *, category as category_name
                    FROM {$this->table}
                    WHERE hotel_id = ? AND is_available = 1";
            $params = [$hotelId];

            if ($serviceTime) {
                $sql .= " AND (service_time = ? OR service_time = 'all_day')";
                $params[] = $serviceTime;
            }

            $sql .= " ORDER BY category, name";
        }

        return $this->db->select($sql, $params);
    }

    public function toggleAvailability($dishId) {
        $sql = "UPDATE {$this->table} SET is_available = NOT is_available WHERE id = ?";
        return $this->db->update($sql, [$dishId]);
    }
}