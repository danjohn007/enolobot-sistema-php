<?php
class WinesController extends Controller {
    private $wineModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['hotel_admin', 'hostess', 'collaborator']);
        $this->wineModel = $this->loadModel('Wine');
    }

    public function index() {
        $role = $_SESSION['role'];
        if ($role === 'superadmin') {
            $wines = $this->wineModel->getAllWines();
        } else {
            $hotelId = $_SESSION['hotel_id'];
            $wines = $this->wineModel->getWinesByHotel($hotelId);
        }

        $data = [
            'title' => 'Vinos',
            'wines' => $wines,
            'role' => $role
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('wines/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $this->requireRole('hotel_admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotelId = $_SESSION['hotel_id'];

            $data = [
                'hotel_id'      => $hotelId,
                'name'          => trim($_POST['name']),
                'description'   => trim($_POST['description'] ?? ''),
                'details'       => trim($_POST['details'] ?? ''),
                'suggested_for' => trim($_POST['suggested_for'] ?? ''),
                'price'         => floatval($_POST['price'] ?? 0),
                'image_path'    => trim($_POST['image_path'] ?? ''),
                'display_order' => intval($_POST['display_order'] ?? 0),
                'is_active'     => 1
            ];

            try {
                $this->wineModel->create($data);
                $_SESSION['success_message'] = 'Vino agregado exitosamente';
                $this->redirect('/wines');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al agregar vino: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Agregar Vino',
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('wines/create', $data);
        $this->loadView('layouts/footer');
    }

    public function edit($id) {
        $this->requireRole('hotel_admin');
        $hotelId = $_SESSION['hotel_id'] ?? null;

        $wine = $this->wineModel->getById($id);
        if (!$wine || ($hotelId !== null && $wine['hotel_id'] != $hotelId)) {
            $this->redirect('/wines');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name'          => trim($_POST['name']),
                'description'   => trim($_POST['description'] ?? ''),
                'details'       => trim($_POST['details'] ?? ''),
                'suggested_for' => trim($_POST['suggested_for'] ?? ''),
                'price'         => floatval($_POST['price'] ?? 0),
                'image_path'    => trim($_POST['image_path'] ?? ''),
                'display_order' => intval($_POST['display_order'] ?? 0),
                'is_active'     => isset($_POST['is_active']) ? 1 : 0,
            ];

            try {
                $this->wineModel->update($id, $data);
                $_SESSION['success_message'] = 'Vino actualizado exitosamente';
                $this->redirect('/wines');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al actualizar vino: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Editar Vino',
            'wine'  => $wine,
            'role'  => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('wines/edit', $data);
        $this->loadView('layouts/footer');
    }

    public function delete($id) {
        $this->requireRole('hotel_admin');
        $hotelId = $_SESSION['hotel_id'] ?? null;

        $wine = $this->wineModel->getById($id);
        if ($wine && ($hotelId === null || $wine['hotel_id'] == $hotelId)) {
            $this->wineModel->update($id, ['is_active' => 0]);
            $_SESSION['success_message'] = 'Vino eliminado exitosamente';
        }

        $this->redirect('/wines');
    }
}
