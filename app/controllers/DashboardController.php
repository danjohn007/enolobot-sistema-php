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
            $result = $this->db->selectOne("SELECT COUNT(*) as count FROM hotels WHERE is_active = 1");
            $stats['total_hotels'] = $result ? $result['count'] : 0;
            
            $result = $this->db->selectOne("SELECT COUNT(*) as count FROM hotels WHERE subscription_status = 'active'");
            $stats['active_subscriptions'] = $result ? $result['count'] : 0;
            
            $result = $this->db->selectOne("SELECT COUNT(*) as count FROM hotels WHERE subscription_status = 'trial'");
            $stats['trial_hotels'] = $result ? $result['count'] : 0;
            
            $result = $this->db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1");
            $stats['total_users'] = $result ? $result['count'] : 0;
        } else {
            // Hotel-specific stats
            if ($hotelId) {
                $result = $this->db->selectOne("SELECT COUNT(*) as count FROM amenities WHERE hotel_id = ? AND is_active = 1", [$hotelId]);
                $stats['total_amenities'] = $result ? $result['count'] : 0;
                
                $result = $this->db->selectOne("SELECT COUNT(*) as count FROM service_requests WHERE hotel_id = ? AND status = 'pending'", [$hotelId]);
                $stats['pending_services'] = $result ? $result['count'] : 0;

                $result = $this->db->selectOne("SELECT COUNT(*) as count FROM wines WHERE hotel_id = ? AND is_active = 1", [$hotelId]);
                $stats['total_wines'] = $result ? $result['count'] : 0;

                // Pending service requests
                $stats['pending_requests'] = $this->db->select("
                    SELECT sr.*, u.first_name, u.last_name
                    FROM service_requests sr
                    LEFT JOIN users u ON sr.guest_id = u.id
                    WHERE sr.hotel_id = ? AND sr.status = 'pending'
                    ORDER BY sr.created_at DESC
                    LIMIT 5
                ", [$hotelId]);
            }
        }

        return $stats;
    }
}