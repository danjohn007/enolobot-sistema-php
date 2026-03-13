<?php
class UsersController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole('hotel_admin');
        $this->userModel = $this->loadModel('User');
    }

    public function index() {
        $hotelId = $_SESSION['hotel_id'];
        $users = $this->userModel->getUsersByHotel($hotelId);

        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('users/index', $data);
        $this->loadView('layouts/footer');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotelId = $_SESSION['hotel_id'];
            
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            
            if ($this->userModel->findByEmail($email)) {
                $_SESSION['error_message'] = 'Este email ya está registrado';
            } else {
                $data = [
                    'hotel_id' => $hotelId,
                    'email' => $email,
                    'password' => $_POST['password'],
                    'first_name' => trim($_POST['first_name']),
                    'last_name' => trim($_POST['last_name']),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'role' => $_POST['role']
                ];

                try {
                    $this->userModel->register($data);
                    $_SESSION['success_message'] = 'Usuario creado exitosamente';
                    $this->redirect('/users');
                } catch (Exception $e) {
                    $_SESSION['error_message'] = 'Error al crear usuario: ' . $e->getMessage();
                }
            }
        }

        $data = [
            'title' => 'Nuevo Usuario',
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('users/create', $data);
        $this->loadView('layouts/footer');
    }

    public function edit($id) {
        $hotelId = $_SESSION['hotel_id'];
        $user = $this->userModel->getById($id);
        
        if (!$user || $user['hotel_id'] != $hotelId) {
            $this->redirect('/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone'] ?? ''),
                'role' => $_POST['role'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            // Update password only if provided
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            try {
                $this->userModel->update($id, $data);
                $_SESSION['success_message'] = 'Usuario actualizado exitosamente';
                $this->redirect('/users');
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error al actualizar usuario: ' . $e->getMessage();
            }
        }

        $data = [
            'title' => 'Editar Usuario',
            'user' => $user,
            'role' => $_SESSION['role']
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('users/edit', $data);
        $this->loadView('layouts/footer');
    }

    public function delete($id) {
        $hotelId = $_SESSION['hotel_id'];
        $user = $this->userModel->getById($id);
        
        if ($user && $user['hotel_id'] == $hotelId && $user['id'] != $_SESSION['user_id']) {
            $this->userModel->update($id, ['is_active' => 0]);
            $_SESSION['success_message'] = 'Usuario desactivado exitosamente';
        }
        
        $this->redirect('/users');
    }

    public function toggleStatus($id) {
        $hotelId = $_SESSION['hotel_id'];
        $user = $this->userModel->getById($id);
        
        if ($user && $user['hotel_id'] == $hotelId && $user['id'] != $_SESSION['user_id']) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $this->userModel->update($id, ['is_active' => $newStatus]);
            $_SESSION['success_message'] = 'Estado actualizado exitosamente';
        }
        
        $this->redirect('/users');
    }
}
