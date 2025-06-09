<?php

namespace App\Models;
use App\Core\EmptyModel;
use PDO;
use Exception;

class User extends EmptyModel {
    public $id;
    public $username;
    public $email;
    public $password;
    public $name;
    public $last_name;
    public $avatar_url;
    public $role;
    public $github_id;

    
    public function __construct() {
        parent::__construct('users');

    }

    public function getUserRole($userId) {
        $query = $this->db->prepare("SELECT role FROM user_roles WHERE user_id = :user_id");
        $query->bindParam(':user_id', $userId);
        $query->execute();
        return $query->fetchColumn();
    }

    /*public function login() {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $query = $this->db->prepare(
                "SELECT u.id, u.username, u.email, u.password, u.name, u.last_name, u.avatar_url, r.title 
                FROM users as u 
                JOIN user_roles as ur 
                ON u.id = ur.user_id 
                JOIN roles as r 
                ON ur.role_id = r.id 
                WHERE username = :username"
                );
            $query->bindParam(':username', $username);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user']['id'] = $user['id'];
                $_SESSION['user']['username'] = $user['username'];
                $_SESSION['user']['email'] = $user['email'];
                $_SESSION['user']['name'] = $user['name'];
                $_SESSION['user']['last_name'] = $user['last_name'];
                $_SESSION['user']['avatar_url'] = $user['avatar_url'];
                $_SESSION['user']['role'] = $user['title'];
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }*/

    public function logout() {
        // Asegura que la sesión esté iniciada antes de manipularla
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Elimina solo los datos del usuario de la sesión
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        // Opcional: destruye la sesión completamente
        // session_destroy();
        return true;
    }

    public function getByUsername($username) {
        $query = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function githubExists($github_id) {
        $query = $this->db->prepare(
            "SELECT u.id 
             FROM users u
             WHERE u.github_id = :github_id"
        );
        $query->bindParam(':github_id', $github_id);
        $query->execute();
        return $query->fetchColumn() !== false;
    }

    public function getByGithubId($github_id) {
        $query = $this->db->prepare(
            "SELECT u.*, r.title AS role
             FROM users u
             JOIN user_roles ur ON u.id = ur.user_id
             JOIN roles r ON ur.role_id = r.id
             WHERE u.github_id = :github_id"
        );
        $query->bindParam(':github_id', $github_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function githubLogin($github_id, $username, $email, $name, $last_name, $avatar_url, $access_token) {
        try {
            $query = $this->db->prepare("INSERT INTO users (github_id, username, email, name, last_name, avatar_url) VALUES (:github_id, :username, :email, :name, :last_name, :avatar_url)");
            $query->bindParam(':github_id', $github_id);
            $query->bindParam(':username', $username);
            $query->bindParam(':email', $email);
            $query->bindParam(':name', $name);
            $query->bindParam(':last_name', $last_name);
            $query->bindParam(':avatar_url', $avatar_url);

            if ($query->execute()) {
                $_SESSION['user'] = [
                    'id' => $this->db->lastInsertId(),
                    'username' => $username,
                    'email' => $email,
                    'name' => $name,
                    'last_name' => $last_name,
                    'avatar_url' => $avatar_url,
                    'role' => 'usuario',
                    'github_id' => $github_id,
                    'access_token' => $access_token,
                ];

                // Obtener el ID del usuario recién insertado
                $user_id = $this->db->lastInsertId();
                // Insertar en tabla user_roles
                $stmt = $this->db->prepare("
                    INSERT INTO user_roles (user_id, role_id)
                    VALUES (:user_id, 1)
                ");
                $stmt->execute([':user_id' => $user_id]);

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getFollowStats($username) {
        $userId = $this->getByUsername($username)['id'];
        $query = $this->db->prepare("
        SELECT
            (SELECT COUNT(*) 
            FROM user_follows 
            WHERE following_user_id = :user_id) AS follows,
            (SELECT COUNT(*) 
            FROM user_follows 
            WHERE followed_user_id = :user_id) AS followers;
        ");
        $query->bindParam(":user_id", $userId);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function follow($followerId, $followedId) {
        echo "Follower ID: $followerId, Followed ID: $followedId";
        $query = $this->db->prepare("INSERT INTO user_follows (following_user_id, followed_user_id, follows_since) VALUES (:follower_id, :followed_id, :follows_since)");
        $followsSince = date('Y-m-d H:i:s');
        $query->bindParam(':follows_since', $followsSince);
        $query->bindParam(':follower_id', $followerId);
        $query->bindParam(':followed_id', $followedId);
        return $query->execute();
    }

    public function unfollow($followerId, $followedId) {
        $query = $this->db->prepare("DELETE FROM user_follows WHERE following_user_id = :follower_id AND followed_user_id = :followed_id");
        $query->bindParam(':follower_id', $followerId);
        $query->bindParam(':followed_id', $followedId);
        return $query->execute();
    }

    public function getUsersCount() {
        $query = $this->db->prepare("SELECT COUNT(*) AS total FROM users");
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getAll($offset = 0, $limit = 100) {
        // Obtener los usuarios paginados
        $query = $this->db->prepare("SELECT id, username, email, name, last_name, avatar_url, github_id FROM users LIMIT :offset, :limit");
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        // Obtener el total de usuarios
        $countQuery = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $countQuery->execute();
        $total = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'data' => $users,
            'total' => $total
        ];
    }

    public function searchUsersByPrefix($prefix) {
        $stmt = $this->db->prepare("SELECT username, avatar_url FROM users WHERE username LIKE ? LIMIT 10");
        $search = $prefix . '%';
        $stmt->execute([$search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editUsername($newUsername, $oldUsername) {
        try {
            $query = $this->db->prepare("UPDATE users SET username = :newUsername WHERE username = :oldUsername");
            $query->bindParam(':newUsername', $newUsername);
            $query->bindParam(':oldUsername', $oldUsername);
            if ($query->execute()) {
            // Si el usuario en sesión es el que se ha cambiado, actualiza la sesión
            if (isset($_SESSION['user']['username']) && $_SESSION['user']['username'] === $oldUsername) {
                $_SESSION['user']['username'] = $newUsername;
            }
            return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateProfileImage($userId, $path) {
        try {
            $query = $this->db->prepare("UPDATE users SET avatar_url = :avatar_url WHERE id = :user_id");
            $query->bindParam(':avatar_url', $path);
            $query->bindParam(':user_id', $userId);
            if ($query->execute()) {
                if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $userId) {
                    $_SESSION['user']['avatar_url'] = $path;
                }
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}