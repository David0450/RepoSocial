<?php

namespace App\Core;

class Security
{
    public static function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        } else {
            session_regenerate_id(true);
        }
        return isset($_SESSION['user']['id']) && is_array($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    public static function isAdmin()
    {
        return isset($_SESSION['user']['role']) && ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'superadmin');
    }

    public static function redirectIfNotLoggedIn($redirectUrl = '/login')
    {
        if (!self::isLoggedIn()) {
            header("Location: $redirectUrl");
            exit();
        }
    }

    public static function redirectIfNotAdmin($redirectUrl = '/')
    {
        if (!self::isAdmin()) {
            header("Location: $redirectUrl");
            exit();
        }
    }

    public static function sanitizeInput($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePassword($password)
    {
        return strlen($password) >= 8; // Example: minimum 8 characters
    }

    public static function validateUsername($username)
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username); // Example: alphanumeric and underscores only
    }

    public static function validateName($name)
    {
        return preg_match('/^[a-zA-Z\s]+$/', $name); // Example: letters and spaces only
    }

    public static function validateLastName($lastName)
    {
        return preg_match('/^[a-zA-Z\s]+$/', $lastName); // Example: letters and spaces only
    }

}