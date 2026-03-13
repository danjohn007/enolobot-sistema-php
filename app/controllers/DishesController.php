<?php
class DishesController extends Controller {
    private $dishModel;
    private $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['hotel_admin', 'restaurant_manager']);
        $this->dishModel = $this->loadModel('Dish');
        $this->categoryModel = $this->loadModel('MenuCategory');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        $dishes = $this->dishModel->getDishesByHotel($hotelId);
        $categories = $this->categoryModel->getCategoriesByHotel($hotelId);

        $data = [
            'title' => 'Gestión de Menú',
            'dishes' => $dishes,
            'categories' => $categories,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('dishes/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        $hotelId = $_SESSION['hotel_id'];
        $categories = $this->categoryModel->getCategoriesByHotel($hotelId);

        if (empty($categories)) {
            $_SESSION['error_message'] = 'Debe crear al menos una categoría antes de agregar platillos';
            $this->redirect('/dishes/categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'hotel_id' => $hotelId,
                'category_id' => intval($_POST['category_id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'price' => floatval($_POST['price']),
                'service_time' => $_POST['service_time'],
                'preparation_time' => intval($_POST['preparation_time'] ?? 15),
                'is_available' => 1
            ];

            try {
                $this->dishModel->create($data);
                $_SESSION['success_message'] = 'Platillo creado exitosamente';
                $this->redirect('/dishes');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al crear platillo: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Nuevo Platillo',
            'categories' => $categories,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('dishes/create', $data);
        $this->loadView('layouts/footer');
    }

    public function edit($id) {
        $hotelId = $_SESSION['hotel_id'];
        $dish = $this->dishModel->getById($id);
        
        if (!$dish || $dish['hotel_id'] != $hotelId) {
            $this->redirect('/dishes');
        }

        $categories = $this->categoryModel->getCategoriesByHotel($hotelId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_id' => intval($_POST['category_id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'price' => floatval($_POST['price']),
                'service_time' => $_POST['service_time'],
                'preparation_time' => intval($_POST['preparation_time'] ?? 15),
                'is_available' => isset($_POST['is_available']) ? 1 : 0
            ];

            try {
                $this->dishModel->update($id, $data);
                $_SESSION['success_message'] = 'Platillo actualizado exitosamente';
                $this->redirect('/dishes');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al actualizar platillo: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Editar Platillo',
            'dish' => $dish,
            'categories' => $categories,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('dishes/edit', $data);
        $this->loadView('layouts/footer');
    }

    public function delete($id) {
        $hotelId = $_SESSION['hotel_id'];
        $dish = $this->dishModel->getById($id);
        
        if ($dish && $dish['hotel_id'] == $hotelId) {
            $this->dishModel->update($id, ['is_active' => 0]);
            $_SESSION['success_message'] = 'Platillo eliminado exitosamente';
        }
        
        $this->redirect('/dishes');
    }

    public function toggleAvailability($id) {
        $hotelId = $_SESSION['hotel_id'];
        $dish = $this->dishModel->getById($id);
        
        if ($dish && $dish['hotel_id'] == $hotelId) {
            $this->dishModel->toggleAvailability($id);
            $_SESSION['success_message'] = 'Disponibilidad actualizada';
        }
        
        $this->redirect('/dishes');
    }

    public function categories() {
        $hotelId = $_SESSION['hotel_id'];
        $categories = $this->categoryModel->getCategoriesByHotel($hotelId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'create') {
                $data = [
                    'hotel_id' => $hotelId,
                    'name' => trim($_POST['name']),
                    'description' => trim($_POST['description'] ?? ''),
                    'display_order' => intval($_POST['display_order'] ?? 0)
                ];
                $this->categoryModel->create($data);
                $_SESSION['success_message'] = 'Categoría creada exitosamente';
            } elseif ($_POST['action'] === 'delete' && isset($_POST['category_id'])) {
                $this->categoryModel->update($_POST['category_id'], ['is_active' => 0]);
                $_SESSION['success_message'] = 'Categoría eliminada exitosamente';
            }
            $this->redirect('/dishes/categories');
        }

        $data = [
            'title' => 'Categorías del Menú',
            'categories' => $categories,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('dishes/categories', $data);
        $this->loadView('layouts/footer');
    }
}
