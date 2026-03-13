<?php
class AmenitiesController extends Controller {
    private $amenityModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['hotel_admin', 'hostess', 'collaborator']);
        $this->amenityModel = $this->loadModel('Amenity');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        $amenities = $this->amenityModel->getAmenitiesByHotel($hotelId);

        $data = [
            'title' => 'Gestión de Amenidades',
            'amenities' => $amenities,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('amenities/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $this->requireRole('hotel_admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotelId = $_SESSION['hotel_id'];
            
            $data = [
                'hotel_id' => $hotelId,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'capacity' => intval($_POST['capacity'] ?? 1),
                'price' => floatval($_POST['price'] ?? 0),
                'operating_hours_start' => !empty($_POST['operating_hours_start']) ? $_POST['operating_hours_start'] : null,
                'operating_hours_end' => !empty($_POST['operating_hours_end']) ? $_POST['operating_hours_end'] : null,
                'status' => 'available'
            ];

            try {
                $this->amenityModel->create($data);
                $_SESSION['success_message'] = 'Amenidad creada exitosamente';
                $this->redirect('/amenities');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al crear amenidad: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Nueva Amenidad',
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('amenities/create', $data);
        $this->loadView('layouts/footer');
    }

    public function edit($id) {
        $this->requireRole('hotel_admin');
        $hotelId = $_SESSION['hotel_id'];
        
        $amenity = $this->amenityModel->getById($id);
        if (!$amenity || $amenity['hotel_id'] != $hotelId) {
            $this->redirect('/amenities');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'capacity' => intval($_POST['capacity'] ?? 1),
                'price' => floatval($_POST['price'] ?? 0),
                'operating_hours_start' => !empty($_POST['operating_hours_start']) ? $_POST['operating_hours_start'] : null,
                'operating_hours_end' => !empty($_POST['operating_hours_end']) ? $_POST['operating_hours_end'] : null,
                'status' => $_POST['status']
            ];

            try {
                $this->amenityModel->update($id, $data);
                $_SESSION['success_message'] = 'Amenidad actualizada exitosamente';
                $this->redirect('/amenities');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al actualizar amenidad: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Editar Amenidad',
            'amenity' => $amenity,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('amenities/edit', $data);
        $this->loadView('layouts/footer');
    }

    public function delete($id) {
        $this->requireRole('hotel_admin');
        $hotelId = $_SESSION['hotel_id'];
        
        $amenity = $this->amenityModel->getById($id);
        if ($amenity && $amenity['hotel_id'] == $hotelId) {
            $this->amenityModel->update($id, ['is_active' => 0]);
            $_SESSION['success_message'] = 'Amenidad eliminada exitosamente';
        }
        
        $this->redirect('/amenities');
    }

    public function changeStatus($id) {
        $hotelId = $_SESSION['hotel_id'];
        $amenity = $this->amenityModel->getById($id);
        
        if ($amenity && $amenity['hotel_id'] == $hotelId) {
            $newStatus = $_POST['status'] ?? 'available';
            $this->amenityModel->updateStatus($id, $newStatus);
            $_SESSION['success_message'] = 'Estado actualizado exitosamente';
        }
        
        $this->redirect('/amenities');
    }
}
