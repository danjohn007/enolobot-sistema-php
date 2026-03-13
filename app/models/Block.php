<?php
class Block extends Model {
    protected $table = 'blocks';

    public function getActiveBlocks($hotelId) {
        $sql = "SELECT b.*, u.first_name, u.last_name,
                CASE 
                    WHEN b.resource_type = 'room' THEN (SELECT room_number FROM rooms WHERE id = b.resource_id)
                    WHEN b.resource_type = 'table' THEN (SELECT table_number FROM restaurant_tables WHERE id = b.resource_id)
                    WHEN b.resource_type = 'amenity' THEN (SELECT name FROM amenities WHERE id = b.resource_id)
                END as resource_name
                FROM {$this->table} b
                LEFT JOIN users u ON b.blocked_by = u.id
                WHERE b.hotel_id = ? AND b.is_active = 1
                ORDER BY b.created_at DESC";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getBlocksByResource($resourceType, $resourceId) {
        $sql = "SELECT b.*, u.first_name, u.last_name
                FROM {$this->table} b
                LEFT JOIN users u ON b.blocked_by = u.id
                WHERE b.resource_type = ? AND b.resource_id = ? AND b.is_active = 1
                ORDER BY b.created_at DESC";
        return $this->db->select($sql, [$resourceType, $resourceId]);
    }

    public function createBlock($data) {
        return $this->create($data);
    }

    public function releaseBlock($blockId, $userId) {
        $sql = "UPDATE {$this->table} 
                SET is_active = 0, released_by = ?, released_at = NOW() 
                WHERE id = ?";
        return $this->db->update($sql, [$userId, $blockId]);
    }

    public function autoReleaseExpiredBlocks($hotelId) {
        $sql = "UPDATE {$this->table} 
                SET is_active = 0 
                WHERE hotel_id = ? 
                AND is_active = 1 
                AND end_datetime IS NOT NULL 
                AND end_datetime < NOW()";
        return $this->db->update($sql, [$hotelId]);
    }
}
