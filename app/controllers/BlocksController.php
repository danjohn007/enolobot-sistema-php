<?php
class BlocksController extends Controller {
    private $blockModel;
    private $roomModel;
    private $tableModel;
    private $amenityModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['hotel_admin', 'hostess']);
        $this->blockModel = $this->loadModel('Block');
        $this->roomModel = $this->loadModel('Room');
        $this->tableModel = $this->loadModel('RestaurantTable');
        $this->amenityModel = $this->loadModel('Amenity');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        
        // Auto-release expired blocks
        $this->blockModel->autoReleaseExpiredBlocks($hotelId);
        
        $blocks = $this->blockModel->getActiveBlocks($hotelId);

        $data = [
            'title' => 'Gestión de Bloqueos',
            'blocks' => $blocks,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('blocks/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $hotelId = $_SESSION['hotel_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resourceType = $_POST['resource_type'];
            $resourceId = intval($_POST['resource_id']);
            
            $data = [
                'hotel_id' => $hotelId,
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'blocked_by' => $_SESSION['user_id'],
                'reason' => trim($_POST['reason']),
                'start_datetime' => $_POST['start_datetime'],
                'end_datetime' => !empty($_POST['end_datetime']) ? $_POST['end_datetime'] : null,
                'is_active' => 1
            ];

            try {
                $this->blockModel->createBlock($data);
                
                // Update resource status to blocked
                if ($resourceType === 'room') {
                    $this->roomModel->updateStatus($resourceId, 'blocked');
                } elseif ($resourceType === 'table') {
                    $this->tableModel->updateStatus($resourceId, 'blocked');
                } elseif ($resourceType === 'amenity') {
                    $this->amenityModel->updateStatus($resourceId, 'blocked');
                }
                
                $_SESSION['success_message'] = 'Bloqueo creado exitosamente';
                $this->redirect('/blocks');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al crear bloqueo: ' . $e->getMessage();
            }
        }

        // Get all resources
        $rooms = $this->roomModel->getRoomsByHotel($hotelId);
        $tables = $this->tableModel->getTablesByHotel($hotelId);
        $amenities = $this->amenityModel->getAmenitiesByHotel($hotelId);

        $data = [
            'title' => 'Nuevo Bloqueo',
            'rooms' => $rooms,
            'tables' => $tables,
            'amenities' => $amenities,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('blocks/create', $data);
        $this->loadView('layouts/footer');
    }

    public function release($id) {
        $hotelId = $_SESSION['hotel_id'];
        $block = $this->blockModel->getById($id);
        
        if ($block && $block['hotel_id'] == $hotelId) {
            $this->blockModel->releaseBlock($id, $_SESSION['user_id']);
            
            // Update resource status to available
            if ($block['resource_type'] === 'room') {
                $this->roomModel->updateStatus($block['resource_id'], 'available');
            } elseif ($block['resource_type'] === 'table') {
                $this->tableModel->updateStatus($block['resource_id'], 'available');
            } elseif ($block['resource_type'] === 'amenity') {
                $this->amenityModel->updateStatus($block['resource_id'], 'available');
            }
            
            $_SESSION['success_message'] = 'Bloqueo liberado exitosamente';
        }
        
        $this->redirect('/blocks');
    }

    public function history() {
        $hotelId = $_SESSION['hotel_id'];
        
        $sql = "SELECT b.*, u.first_name, u.last_name, r.first_name as released_first_name, r.last_name as released_last_name,
                CASE 
                    WHEN b.resource_type = 'room' THEN (SELECT room_number FROM rooms WHERE id = b.resource_id)
                    WHEN b.resource_type = 'table' THEN (SELECT table_number FROM restaurant_tables WHERE id = b.resource_id)
                    WHEN b.resource_type = 'amenity' THEN (SELECT name FROM amenities WHERE id = b.resource_id)
                END as resource_name
                FROM blocks b
                LEFT JOIN users u ON b.blocked_by = u.id
                LEFT JOIN users r ON b.released_by = r.id
                WHERE b.hotel_id = ?
                ORDER BY b.created_at DESC
                LIMIT 100";
        $blocks = $this->db->select($sql, [$hotelId]);

        $data = [
            'title' => 'Historial de Bloqueos',
            'blocks' => $blocks,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('blocks/history', $data);
        $this->loadView('layouts/footer');
    }
}
