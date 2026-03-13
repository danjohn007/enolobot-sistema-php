<?php
class AuthController extends Controller {
    private $userModel;
    private $hotelModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = $this->loadModel('User');
        $this->hotelModel = $this->loadModel('Hotel');
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Por favor complete todos los campos';
            } else {
                $user = $this->userModel->authenticate($email, $password);
                if ($user) {
                    if ($user['is_active']) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['hotel_id'] = $user['hotel_id'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['role'] = $user['role'];
                        
                        $this->redirect('/dashboard');
                    } else {
                        $error = 'Su cuenta está desactivada';
                    }
                } else {
                    $error = 'Credenciales inválidas';
                }
            }
        }

        $data = [
            'title' => 'Iniciar Sesión',
            'error' => $error ?? null
        ];
        $this->loadView('layouts/header', $data);
        $this->loadView('auth/login', $data);
        $this->loadView('layouts/footer');
    }

    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $hotelName = trim($_POST['hotel_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $planId = intval($_POST['plan_id'] ?? 1);

            $errors = [];

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            }
            if (empty($password) || strlen($password) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Las contraseñas no coinciden';
            }
            if (empty($firstName) || empty($lastName)) {
                $errors[] = 'Nombre y apellido son requeridos';
            }
            if (empty($hotelName)) {
                $errors[] = 'El nombre del hotel es requerido';
            }

            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Este email ya está registrado';
            }

            if (empty($errors)) {
                try {
                    $this->db->getConnection()->beginTransaction();

                    // Get subscription plan
                    $planSql = "SELECT * FROM subscription_plans WHERE id = ?";
                    $plan = $this->db->selectOne($planSql, [$planId]);

                    $subscriptionStart = date('Y-m-d');
                    $daysToAdd = $plan['billing_period'] === 'annual' ? 365 : 
                                ($plan['billing_period'] === 'monthly' ? 30 : $plan['trial_days']);
                    $subscriptionEnd = date('Y-m-d', strtotime("+{$daysToAdd} days"));
                    $subscriptionStatus = $plan['billing_period'] === 'trial' ? 'trial' : 'active';

                    // Create hotel
                    $hotelData = [
                        'name' => $hotelName,
                        'email' => $email,
                        'phone' => $phone,
                        'subscription_plan_id' => $planId,
                        'subscription_status' => $subscriptionStatus,
                        'subscription_start' => $subscriptionStart,
                        'subscription_end' => $subscriptionEnd
                    ];
                    $hotelId = $this->hotelModel->create($hotelData);

                    // Create admin user
                    $userData = [
                        'hotel_id' => $hotelId,
                        'email' => $email,
                        'password' => $password,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $phone,
                        'role' => 'hotel_admin'
                    ];
                    $userId = $this->userModel->register($userData);

                    $this->db->getConnection()->commit();

                    $_SESSION['success_message'] = 'Registro exitoso. Por favor inicie sesión.';
                    $this->redirect('/auth/login');
                } catch (Exception $e) {
                    $this->db->getConnection()->rollBack();
                    $errors[] = 'Error al registrar: ' . $e->getMessage();
                }
            }
        }

        // Get subscription plans
        $plansSql = "SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY price";
        $plans = $this->db->select($plansSql);

        $data = [
            'title' => 'Registro',
            'errors' => $errors ?? [],
            'plans' => $plans,
            'selectedPlan' => intval($_GET['plan'] ?? 1)
        ];
        $this->loadView('layouts/header', $data);
        $this->loadView('auth/register', $data);
        $this->loadView('layouts/footer');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/auth/login');
    }

    public function forgot() {
        $data = ['title' => 'Recuperar Contraseña'];
        $this->loadView('layouts/header', $data);
        $this->loadView('auth/forgot', $data);
        $this->loadView('layouts/footer');
    }
}
