<?php

namespace App\Controllers;
use App\Core\Security;
use App\Core\Config;

class MainController {

    public function renderLanding() {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for landing page
        require __DIR__ . '/../Views/other/landing.php';
        exit();
    }

    public function renderLogin() {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for login
        require __DIR__ . '/../Views/other/loginView.php';
        exit();
    }

    public function renderSignup() {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for signup
        require __DIR__ . '/../Views/other/signupView.php';
        exit();
    }   

    public function renderHome() {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for home
        require __DIR__ . '/../Views/other/landing.php';
        exit();
    }

    public function renderProjects() {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for projects
        require __DIR__ . '/../Views/projects/projects_list.php';
        exit();
    }

    public function renderProjectCreate() {
        $isLoggedIn = Security::isLoggedIn();
        if (!$isLoggedIn) {
            header('Location: ' . Config::PATH . 'login');
            exit();
        }
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for project creation
        require __DIR__ . '/../Views/projects/projects_create.php';
        exit();
    }

    public function renderProfile($user) {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for profile
        require __DIR__ . '/../Views/user/profile.php';
        exit();
    }

    public function renderAccount($user) {
        $isLoggedIn = Security::isLoggedIn();
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for profile
        require __DIR__ . '/../Views/user/account.php';
        exit();
    }

    public function renderChat() {
        $isLoggedIn = Security::isLoggedIn();
        if (!$isLoggedIn) {
            header('Location: ' . Config::PATH . 'login');
            exit();
        }
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for chat
        require __DIR__ . '/../Views/chats/chat_view.php';
        exit();
    }

    public function renderAdminDashboard() {
        $isLoggedIn = Security::isLoggedIn();
        if (!$isLoggedIn || !Security::isAdmin()) {
            header('Location: ' . Config::PATH . 'home');
            exit();
        }
        $PATH = Config::PATH;
        $isAdmin = Security::isAdmin();
        // Logic for admin dashboard
        require __DIR__ . '/../Views/admin/dashboard.php';
        exit();
    }
}