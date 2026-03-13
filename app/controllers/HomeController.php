<?php
class HomeController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'Bienvenido a MajorBot',
            'isLoggedIn' => $this->isLoggedIn()
        ];
        $this->loadView('layouts/header', $data);
        $this->loadView('home/index', $data);
        $this->loadView('layouts/footer');
    }
}
