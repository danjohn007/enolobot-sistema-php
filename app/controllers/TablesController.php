<?php
class TablesController extends Controller {
    private $tableModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['hotel_admin', 'restaurant_manager', 'hostess']);
        $this->tableModel = $this->loadModel('RestaurantTable');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        $tables = $this->tableModel->getTablesByHotel($hotelId);

        $data = [
            'title' => 'Gestión de Mesas',
            'tables' => $tables,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('tables/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $this->requireRole(['hotel_admin', 'restaurant_manager']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotelId = $_SESSION['hotel_id'];
            
            $data = [
                'hotel_id' => $hotelId,
                'table_number' => trim($_POST['table_number']),
                'capacity' => intval($_POST['capacity']),
                'location' => trim($_POST['location'] ?? ''),
                'status' => 'available'
            ];

            try {
                $this->tableModel->create($data);
                $_SESSION['success_message'] = 'Mesa creada exitosamente';
                $this->redirect('/tables');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al crear mesa: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Nueva Mesa',
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('tables/create', $data);
        $this->loadView('layouts/footer');
    }

    public function edit($id) {
        $this->requireRole(['hotel_admin', 'restaurant_manager']);
        $hotelId = $_SESSION['hotel_id'];
        
        $table = $this->tableModel->getById($id);
        if (!$table || $table['hotel_id'] != $hotelId) {
            $this->redirect('/tables');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'table_number' => trim($_POST['table_number']),
                'capacity' => intval($_POST['capacity']),
                'location' => trim($_POST['location'] ?? ''),
                'status' => $_POST['status']
            ];

            try {
                $this->tableModel->update($id, $data);
                $_SESSION['success_message'] = 'Mesa actualizada exitosamente';
                $this->redirect('/tables');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al actualizar mesa: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Editar Mesa',
            'table' => $table,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('tables/edit', $data);
        $this->loadView('layouts/footer');
    }

    public function delete($id) {
        $this->requireRole(['hotel_admin', 'restaurant_manager']);
        $hotelId = $_SESSION['hotel_id'];
        
        $table = $this->tableModel->getById($id);
        if ($table && $table['hotel_id'] == $hotelId) {
            $this->tableModel->update($id, ['is_active' => 0]);
            $_SESSION['success_message'] = 'Mesa eliminada exitosamente';
        }
        
        $this->redirect('/tables');
    }

    public function changeStatus($id) {
        $hotelId = $_SESSION['hotel_id'];
        $table = $this->tableModel->getById($id);
        
        if ($table && $table['hotel_id'] == $hotelId) {
            $newStatus = $_POST['status'] ?? 'available';
            $this->tableModel->updateStatus($id, $newStatus);
            $_SESSION['success_message'] = 'Estado actualizado exitosamente';
        }
        
        $this->redirect('/tables');
    }
}
