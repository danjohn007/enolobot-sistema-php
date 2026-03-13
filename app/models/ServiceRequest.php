<?php
class ServiceRequest extends Model {
    protected $table = 'service_requests';

    public function getRequestsByHotel($hotelId, $status = null) {
        $sql = "SELECT sr.*, u.first_name, u.last_name, r.room_number, a.first_name as assigned_first_name, a.last_name as assigned_last_name
                FROM {$this->table} sr
                LEFT JOIN users u ON sr.guest_id = u.id
                LEFT JOIN rooms r ON sr.room_id = r.id
                LEFT JOIN users a ON sr.assigned_to = a.id
                WHERE sr.hotel_id = ?";
        $params = [$hotelId];
        
        if ($status) {
            $sql .= " AND sr.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY sr.created_at DESC";
        return $this->db->select($sql, $params);
    }

    public function getPendingRequests($hotelId) {
        return $this->getRequestsByHotel($hotelId, 'pending');
    }

    public function assignRequest($requestId, $userId) {
        $sql = "UPDATE {$this->table} SET assigned_to = ?, status = 'assigned' WHERE id = ?";
        return $this->db->update($sql, [$userId, $requestId]);
    }

    public function updateStatus($requestId, $status) {
        $data = ['status' => $status];
        if ($status === 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        return $this->update($requestId, $data);
    }

    public function getMyRequests($userId) {
        $sql = "SELECT sr.*, r.room_number
                FROM {$this->table} sr
                LEFT JOIN rooms r ON sr.room_id = r.id
                WHERE sr.assigned_to = ? 
                ORDER BY sr.status = 'pending' DESC, sr.priority DESC, sr.created_at DESC";
        return $this->db->select($sql, [$userId]);
    }

    public function getGuestRequests($guestId) {
        $sql = "SELECT sr.*, r.room_number
                FROM {$this->table} sr
                LEFT JOIN rooms r ON sr.room_id = r.id
                WHERE sr.guest_id = ? 
                ORDER BY sr.created_at DESC";
        return $this->db->select($sql, [$guestId]);
    }
}
