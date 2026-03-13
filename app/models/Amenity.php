<?php
class Amenity extends Model {
    protected $table = 'amenities';

    public function getAmenitiesByHotel($hotelId) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1 ORDER BY name";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getAvailableAmenities($hotelId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE hotel_id = ? AND status = 'available' AND is_active = 1 
                ORDER BY name";
        return $this->db->select($sql, [$hotelId]);
    }

    public function updateStatus($amenityId, $status) {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->db->update($sql, [$status, $amenityId]);
    }

    public function searchAmenities($hotelId, $filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1";
        $params = [$hotelId];

        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY name";
        return $this->db->select($sql, $params);
    }
}
