<?php
class DashboardController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        $role = $_SESSION['role'];
        $hotelId = $_SESSION['hotel_id'];

        // Get statistics based on role
        $stats = $this->getStatistics($role, $hotelId);

        $data = [
            'title' => 'Dashboard',
            'stats' => $stats,
            'role' => $role,
            'hotelId' => $hotelId
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('dashboard/index', $data);
        $this->loadView('layouts/footer');
    }

    private function getStatistics($role, $hotelId) {
        $stats = [];

        if ($role === 'superadmin') {
            // Superadmin stats
            $stats['total_hotels'] = $this->db->selectOne("SELECT COUNT(*) as count FROM hotels WHERE is_active = 1")['count'];
            $stats['active_subscriptions'] = $this->db->selectOne("SELECT COUNT(*) as count FROM hotels WHERE subscription_status = 'active'")['count'];
            $stats['trial_hotels'] = $this->db->selectOne("SELECT COUNT(*) as count FROM hotels WHERE subscription_status = 'trial'")['count'];
            $stats['total_users'] = $this->db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];
        } else {
            // Hotel-specific stats
            if ($hotelId) {
                $stats['total_rooms'] = $this->db->selectOne("SELECT COUNT(*) as count FROM rooms WHERE hotel_id = ? AND is_active = 1", [$hotelId])['count'];
                $stats['available_rooms'] = $this->db->selectOne("SELECT COUNT(*) as count FROM rooms WHERE hotel_id = ? AND status = 'available' AND is_active = 1", [$hotelId])['count'];
                $stats['total_tables'] = $this->db->selectOne("SELECT COUNT(*) as count FROM restaurant_tables WHERE hotel_id = ? AND is_active = 1", [$hotelId])['count'];
                $stats['available_tables'] = $this->db->selectOne("SELECT COUNT(*) as count FROM restaurant_tables WHERE hotel_id = ? AND status = 'available' AND is_active = 1", [$hotelId])['count'];
                $stats['total_amenities'] = $this->db->selectOne("SELECT COUNT(*) as count FROM amenities WHERE hotel_id = ? AND is_active = 1", [$hotelId])['count'];
                $stats['pending_services'] = $this->db->selectOne("SELECT COUNT(*) as count FROM service_requests WHERE hotel_id = ? AND status = 'pending'", [$hotelId])['count'];
                
                // Recent reservations
                $stats['recent_reservations'] = $this->db->select("
                    SELECT r.*, u.first_name, u.last_name, ro.room_number 
                    FROM room_reservations r
                    LEFT JOIN users u ON r.guest_id = u.id
                    LEFT JOIN rooms ro ON r.room_id = ro.id
                    WHERE r.hotel_id = ?
                    ORDER BY r.created_at DESC
                    LIMIT 5
                ", [$hotelId]);

                // Pending service requests
                $stats['pending_requests'] = $this->db->select("
                    SELECT sr.*, u.first_name, u.last_name, r.room_number
                    FROM service_requests sr
                    LEFT JOIN users u ON sr.guest_id = u.id
                    LEFT JOIN rooms r ON sr.room_id = r.id
                    WHERE sr.hotel_id = ? AND sr.status = 'pending'
                    ORDER BY sr.created_at DESC
                    LIMIT 5
                ", [$hotelId]);
            }
        }

        return $stats;
    }
}
