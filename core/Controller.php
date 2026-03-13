<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function loadModel($model) {
        $modelPath = __DIR__ . '/../app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        }
        return null;
    }

    protected function loadView($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: " . $view);
        }
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . "/" . ltrim($url, '/'));
        exit;
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }

    protected function hasRole($roles) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        $userRole = $_SESSION['role'] ?? '';
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        return $userRole === $roles;
    }

    protected function requireRole($roles) {
        $this->requireLogin();
        if (!$this->hasRole($roles)) {
            $this->redirect('/dashboard');
        }
    }
}
