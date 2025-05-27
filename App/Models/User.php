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

    
    public function __construct(/*$id = null, $username = null, $email = null, $password = null, $name = null, $last_name = null, $avatar_url = null, $role = null, $github_id = null*/) {
        parent::__construct('users');
        /*$this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->last_name = $last_name;
        $this->avatar_url = $avatar_url;
        $this->role = $role;
        $this->github_id = $github_id;*/
    }

    public function login() {
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
                //session_start();
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
            // Handle exception (e.g., log it)
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getUserRole($userId) {
        $query = $this->db->prepare("SELECT role FROM user_roles WHERE user_id = :user_id");
        $query->bindParam(':user_id', $userId);
        $query->execute();
        return $query->fetchColumn();
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

            $query = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
            $userId = $this->db->lastInsertId();
            $roleId = 1;
            $query->bindParam(':user_id', $userId);
            $query->bindParam(':role_id', $roleId);
            $query->execute();

            $_SESSION['user']['id'] = $this->db->lastInsertId();
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['last_name'] = $last_name;
            $_SESSION['user']['role'] = 'usuario';


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

    public function getByUsername($username) {
        $query = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function githubExists($github_id) {
        $query = $this->db->prepare("SELECT * FROM users WHERE github_id = :github_id");
        $query->bindParam(':github_id', $github_id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function githubLogin($github_id, $username, $email, $name, $last_name, $avatar_url) {
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
                    'github_id' => $github_id
                ];
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
}