<?php

require_once __DIR__ . '/../Models/User.php';

class UserController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {

        if ($this->userModel->login()) {
            $_POST['uri'] = '';
            $_GET['uri'] = '';
            include __DIR__ . '/../Views/projects/projects_list.php';
        } else {
            include __DIR__ . '/../Views/other/loginView.php';
        }
    }

    public function signup() {
        if ($this->userModel->signup()) {
            $_POST['uri'] = '';
            $_GET['uri'] = '';
            include __DIR__ . '/../Views/projects/projects_list.php';
        } else {
            include __DIR__ . '/../Views/other/signupView.php';
        }
    }

    public function logout() {
        
        $this->userModel->logout();
        $_POST['uri'] = '';
        $_GET['uri'] = '';
        include __DIR__ . '/../Views/projects/projects_list.php';
    }

    public function register() {
        // Logic for registration
        echo "Register page";
    }

    public function profile() {
        include __DIR__ . '/../Views/profile/profile_view.php';
    }

    public function dashboard() {
        // Logic for dashboard
        echo "Dashboard page";
    }
}