<?php

class User extends EmptyModel {
    
    public function __construct() {
        parent::__construct('users');
    }

    public function login() {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $query = $this->db->prepare(
                "SELECT u.id, u.username, u.email, u.password, u.name, u.last_name, u.profile_pic, r.title 
                FROM users as u 
                JOIN user_roles as ur 
                ON u.id = ur.user_id 
                JOIN roles as r 
                ON ur.role_id = r.id 
                WHERE email = :email"
                );
            $query->bindParam(':email', $email);
            $query->execute();

            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                //session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['role'] = $user['title'];
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

    public function githubToken($code = null, $client_id = null, $client_secret = null) {        
        
        $token_url = "https://github.com/login/oauth/access_token";
        $ch = curl_init($token_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $code
        ]));
        curl_setopt($ch, CURLOPT_CAINFO, "C:/wamp64/www/programacion/PFC/App/cacert.pem"); // Ruta de tu cacert.pem
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            die('Error en cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            die('Error al obtener el token de acceso. Respuesta cruda de GitHub: ' . $response);
        }
        
        $access_token = $data['access_token'];
        echo "Access Token obtenido: " . $access_token;            
    }
}