<?php
class Router {
    private $controller = DEFAULT_CONTROLLER;
    private $method = DEFAULT_METHOD;
    private $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        
        // Check for controller
        if (isset($url[0]) && !empty($url[0])) {
            $controllerPath = __DIR__ . '/../app/controllers/' . ucfirst($url[0]) . 'Controller.php';
            if (file_exists($controllerPath)) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        // Require controller
        $controllerFile = __DIR__ . '/../app/controllers/' . $this->controller . 'Controller.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = $this->controller . 'Controller';
            $this->controller = new $controllerClass;
        } else {
            $this->show404();
            return;
        }

        // Check for method
        if (isset($url[1]) && !empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    private function show404() {
        http_response_code(404);
        echo "404 - Page Not Found";
        exit;
    }
}
