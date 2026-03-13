<?php
class Room extends Model {
    protected $table = 'rooms';

    public function getRoomsByHotel($hotelId) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1 ORDER BY room_number";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getAvailableRooms($hotelId, $checkIn, $checkOut) {
        $sql = "SELECT r.* FROM {$this->table} r 
                WHERE r.hotel_id = ? 
                AND r.status = 'available' 
                AND r.is_active = 1
                AND r.id NOT IN (
                    SELECT room_id FROM room_reservations 
                    WHERE hotel_id = ?
                    AND status NOT IN ('cancelled')
                    AND (
                        (check_in <= ? AND check_out >= ?)
                        OR (check_in <= ? AND check_out >= ?)
                        OR (check_in >= ? AND check_out <= ?)
                    )
                )
                ORDER BY r.room_number";
        return $this->db->select($sql, [$hotelId, $hotelId, $checkIn, $checkIn, $checkOut, $checkOut, $checkIn, $checkOut]);
    }

    public function updateStatus($roomId, $status) {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->db->update($sql, [$status, $roomId]);
    }

    public function searchRooms($hotelId, $filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1";
        $params = [$hotelId];

        if (!empty($filters['room_type'])) {
            $sql .= " AND room_type = ?";
            $params[] = $filters['room_type'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['min_capacity'])) {
            $sql .= " AND capacity >= ?";
            $params[] = $filters['min_capacity'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price_per_night <= ?";
            $params[] = $filters['max_price'];
        }

        $sql .= " ORDER BY room_number";
        return $this->db->select($sql, $params);
    }
}
