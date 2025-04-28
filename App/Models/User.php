<?php

class User extends EmptyModel {
    
    public function __construct() {
        parent::__construct('users');
    }

    public function login() {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $query = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $query->bindParam(':email', $email);
            $query->execute();

            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                //session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Handle exception (e.g., log it)
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function signup() {
        try {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $name = $_POST['name'];
            $last_name = $_POST['last_name'];

            $query = $this->db->prepare("INSERT INTO users (username, email, password, name, last_name) VALUES (:username, :email, :password, :name, :last_name)");
            $query->bindParam(':username', $username);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->bindParam(':name', $name);
            $query->bindParam(':last_name', $last_name);

            $query->execute();

            $_SESSION['user_id'] = $this->db->lastInsertId();
            $_SESSION['username'] = $username;

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }
}