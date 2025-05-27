<?php

namespace App\Controllers;
use App\Controllers\UserController;
use App\Models\User;
use App\Core\Config;
use App\Core\Security;

class AdminController extends UserController {
    public function index() {
        if (!Security::isLoggedIn() || !Security::isAdmin()) {
            header('Location: ' . Config::PATH . 'home');
            exit();
        }

        $this->renderAdminDashboard();
    }
}