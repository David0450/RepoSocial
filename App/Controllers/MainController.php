<?php

class MainController {
    public function login() {
        // Logic for login
        require __DIR__ . '/../Views/other/loginView.php';
        exit();
    }

    public function signup() {
        // Logic for signup
        require __DIR__ . '/../Views/other/signupView.php';
        exit();
    }

    public function logout() {
        // Logic for logout
        echo "Logout page";
    }

    public function register() {
        // Logic for registration
        echo "Register page";
    }

    public function dashboard() {
        // Logic for dashboard
        echo "Dashboard page";
    }
}