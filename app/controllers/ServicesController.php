<?php
class ServicesController extends Controller {
    private $serviceModel;
    private $roomModel;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->serviceModel = $this->loadModel('ServiceRequest');
        $this->roomModel = $this->loadModel('Room');
        $this->userModel = $this->loadModel('User');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        $role = $_SESSION['role'];

        if ($role === 'collaborator') {
            $requests = $this->serviceModel->getMyRequests($_SESSION['user_id']);
        } elseif ($role === 'guest') {
            $requests = $this->serviceModel->getGuestRequests($_SESSION['user_id']);
        } else {
            $requests = $this->serviceModel->getRequestsByHotel($hotelId);
        }

        $data = [
            'title' => 'Solicitudes de Servicio',
            'requests' => $requests,
            'role' => $role
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('services/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $hotelId = $_SESSION['hotel_id'];
        $role = $_SESSION['role'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'hotel_id' => $hotelId,
                'guest_id' => $_SESSION['user_id'],
                'room_id' => !empty($_POST['room_id']) ? intval($_POST['room_id']) : null,
                'service_type' => trim($_POST['service_type']),
                'description' => trim($_POST['description']),
                'priority' => $_POST['priority'] ?? 'medium',
                'status' => 'pending'
            ];

            try {
                $this->serviceModel->create($data);
                $_SESSION['success_message'] = 'Solicitud creada exitosamente';
                $this->redirect('/services');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al crear solicitud: ' . $e->getMessage();
            }
        }

        $rooms = [];
        if ($role === 'guest') {
            // Get rooms where guest has active reservations
            $sql = "SELECT DISTINCT r.* FROM rooms r
                    INNER JOIN room_reservations rr ON r.id = rr.room_id
                    WHERE rr.guest_id = ? AND rr.status IN ('confirmed', 'checked_in')";
            $rooms = $this->db->select($sql, [$_SESSION['user_id']]);
        }

        $data = [
            'title' => 'Nueva Solicitud',
            'rooms' => $rooms,
            'role' => $role
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('services/create', $data);
        $this->loadView('layouts/footer');
    }

    public function assign($id) {
        $this->requireRole(['hotel_admin', 'restaurant_manager']);
        $hotelId = $_SESSION['hotel_id'];
        
        $request = $this->serviceModel->getById($id);
        if (!$request || $request['hotel_id'] != $hotelId) {
            $this->redirect('/services');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignedTo = intval($_POST['assigned_to']);
            $this->serviceModel->assignRequest($id, $assignedTo);
            $_SESSION['success_message'] = 'Solicitud asignada exitosamente';
            $this->redirect('/services');
        }

        $collaborators = $this->userModel->getCollaborators($hotelId);

        $data = [
            'title' => 'Asignar Solicitud',
            'request' => $request,
            'collaborators' => $collaborators,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('services/assign', $data);
        $this->loadView('layouts/footer');
    }

    public function updateStatus($id) {
        $hotelId = $_SESSION['hotel_id'];
        $request = $this->serviceModel->getById($id);
        
        if ($request && $request['hotel_id'] == $hotelId) {
            $newStatus = $_POST['status'] ?? 'pending';
            $this->serviceModel->updateStatus($id, $newStatus);
            $_SESSION['success_message'] = 'Estado actualizado exitosamente';
        }
        
        $this->redirect('/services');
    }

    public function view($id) {
        $hotelId = $_SESSION['hotel_id'];
        $role = $_SESSION['role'];
        
        $sql = "SELECT sr.*, u.first_name, u.last_name, u.phone, u.email, r.room_number, 
                a.first_name as assigned_first_name, a.last_name as assigned_last_name
                FROM service_requests sr
                LEFT JOIN users u ON sr.guest_id = u.id
                LEFT JOIN rooms r ON sr.room_id = r.id
                LEFT JOIN users a ON sr.assigned_to = a.id
                WHERE sr.id = ?";
        $request = $this->db->selectOne($sql, [$id]);
        
        if (!$request || $request['hotel_id'] != $hotelId) {
            $this->redirect('/services');
        }

        $data = [
            'title' => 'Detalle de Solicitud',
            'request' => $request,
            'role' => $role
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('services/view', $data);
        $this->loadView('layouts/footer');
    }
}
