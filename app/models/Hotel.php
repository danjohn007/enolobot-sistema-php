<?php
class Hotel extends Model {
    protected $table = 'hotels';

    public function getActiveHotels() {
        $sql = "SELECT h.*, sp.name as plan_name FROM {$this->table} h 
                LEFT JOIN subscription_plans sp ON h.subscription_plan_id = sp.id 
                WHERE h.is_active = 1 
                ORDER BY h.name";
        return $this->db->select($sql);
    }

    public function getHotelWithPlan($hotelId) {
        $sql = "SELECT h.*, sp.name as plan_name, sp.price as plan_price, 
                sp.billing_period as plan_period 
                FROM {$this->table} h 
                LEFT JOIN subscription_plans sp ON h.subscription_plan_id = sp.id 
                WHERE h.id = ?";
        return $this->db->selectOne($sql, [$hotelId]);
    }

    public function updateSubscriptionStatus($hotelId, $status) {
        $sql = "UPDATE {$this->table} SET subscription_status = ? WHERE id = ?";
        return $this->db->update($sql, [$status, $hotelId]);
    }

    public function checkSubscriptionExpiry($hotelId) {
        $hotel = $this->getById($hotelId);
        if ($hotel && $hotel['subscription_end']) {
            $endDate = strtotime($hotel['subscription_end']);
            $today = strtotime(date('Y-m-d'));
            if ($today > $endDate) {
                $this->updateSubscriptionStatus($hotelId, 'expired');
                return false;
            }
        }
        return true;
    }
}
