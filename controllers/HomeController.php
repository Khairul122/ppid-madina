<?php
class HomeController {
    public function index() {
        // Redirect ke halaman login
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
    
    public function about() {
        // Redirect ke halaman login
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}
?>