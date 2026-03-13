<?php
class RoomsController extends Controller {
    private $roomModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['hotel_admin', 'hostess', 'collaborator']);
        $this->roomModel = $this->loadModel('Room');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        $rooms = $this->roomModel->getRoomsByHotel($hotelId);

        $data = [
            'title' => 'Gestión de Habitaciones',
            'rooms' => $rooms,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('rooms/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $this->requireRole('hotel_admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotelId = $_SESSION['hotel_id'];
            
            $data = [
                'hotel_id' => $hotelId,
                'room_number' => trim($_POST['room_number']),
                'room_type' => $_POST['room_type'],
                'capacity' => intval($_POST['capacity']),
                'price_per_night' => floatval($_POST['price_per_night']),
                'floor' => !empty($_POST['floor']) ? intval($_POST['floor']) : null,
                'description' => trim($_POST['description'] ?? ''),
                'status' => 'available'
            ];

            try {
                $roomId = $this->roomModel->create($data);
                $_SESSION['success_message'] = 'Habitación creada exitosamente';
                $this->redirect('/rooms');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al crear habitación: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Nueva Habitación',
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('rooms/create', $data);
        $this->loadView('layouts/footer');
    }

    public function edit($id) {
        $this->requireRole('hotel_admin');
        $hotelId = $_SESSION['hotel_id'];
        
        $room = $this->roomModel->getById($id);
        if (!$room || $room['hotel_id'] != $hotelId) {
            $this->redirect('/rooms');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'room_number' => trim($_POST['room_number']),
                'room_type' => $_POST['room_type'],
                'capacity' => intval($_POST['capacity']),
                'price_per_night' => floatval($_POST['price_per_night']),
                'floor' => !empty($_POST['floor']) ? intval($_POST['floor']) : null,
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status']
            ];

            try {
                $this->roomModel->update($id, $data);
                $_SESSION['success_message'] = 'Habitación actualizada exitosamente';
                $this->redirect('/rooms');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al actualizar habitación: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Editar Habitación',
            'room' => $room,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('rooms/edit', $data);
        $this->loadView('layouts/footer');
    }

    public function delete($id) {
        $this->requireRole('hotel_admin');
        $hotelId = $_SESSION['hotel_id'];
        
        $room = $this->roomModel->getById($id);
        if ($room && $room['hotel_id'] == $hotelId) {
            $this->roomModel->update($id, ['is_active' => 0]);
            $_SESSION['success_message'] = 'Habitación eliminada exitosamente';
        }
        
        $this->redirect('/rooms');
    }

    public function changeStatus($id) {
        $hotelId = $_SESSION['hotel_id'];
        $room = $this->roomModel->getById($id);
        
        if ($room && $room['hotel_id'] == $hotelId) {
            $newStatus = $_POST['status'] ?? 'available';
            $this->roomModel->updateStatus($id, $newStatus);
            $_SESSION['success_message'] = 'Estado actualizado exitosamente';
        }
        
        $this->redirect('/rooms');
    }
}
