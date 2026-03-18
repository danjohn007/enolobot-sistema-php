<?php
class Amenity extends Model {
    protected $table = 'amenities';

    /** @var array|null Cached column-existence flags for this table */
    private static $schemaCache = null;

    private function schema(): array {
        if (self::$schemaCache === null) {
            $cols = array_column(
                $this->db->select("SHOW COLUMNS FROM {$this->table}"),
                'Field'
            );
            self::$schemaCache = array_flip($cols);
        }
        return self::$schemaCache;
    }

    public function getAmenitiesByHotel($hotelId) {
        $schema = $this->schema();

        if (isset($schema['is_active'])) {
            $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1 ORDER BY name";
        } else {
            $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? ORDER BY name";
        }

        return $this->db->select($sql, [$hotelId]);
    }

    public function getAvailableAmenities($hotelId) {
        $schema = $this->schema();

        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ?";
        if (isset($schema['status'])) {
            $sql .= " AND status = 'available'";
        }
        if (isset($schema['is_active'])) {
            $sql .= " AND is_active = 1";
        }
        $sql .= " ORDER BY name";

        return $this->db->select($sql, [$hotelId]);
    }

    public function updateStatus($amenityId, $status) {
        $schema = $this->schema();
        if (!isset($schema['status'])) {
            return false;
        }
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->db->update($sql, [$status, $amenityId]);
    }

    public function searchAmenities($hotelId, $filters = []) {
        $schema = $this->schema();

        $sql    = "SELECT * FROM {$this->table} WHERE hotel_id = ?";
        $params = [$hotelId];

        if (isset($schema['is_active'])) {
            $sql .= " AND is_active = 1";
        }

        if (isset($schema['status']) && !empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }

        $sql .= " ORDER BY name";
        return $this->db->select($sql, $params);
    }
}