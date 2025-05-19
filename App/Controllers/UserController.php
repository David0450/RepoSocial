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

            header('Location: ' . Config::PATH . 'home');
        } else {
            header('Location: ' . Config::PATH . 'login');
        }
    }

    public function signup() {
        if ($this->userModel->signup()) {
            $_POST['uri'] = '';
            $_GET['uri'] = '';

            header('Location: ' . Config::PATH . 'home');
        } else {
            header('Location: ' . Config::PATH . 'signup');
        }
    }

    public function logout() {
        
        $this->userModel->logout();
        $_POST['uri'] = '';
        $_GET['uri'] = '';
        header('Location: ' . Config::PATH . 'home');
    }

    public function account() {
        include __DIR__ . '/../Views/user/account.php';
    }

    public function profile() {
        if (isset($_GET['parametro'])) {
            $user = $_SESSION['user'];
        }

        include __DIR__ . '/../Views/user/profile.php';
    }

    public function getStoredUserByUsername($username = null) {
        if (isset($_GET['parametro'])) {
            $username = $_GET['parametro'];
            $userData = $this->userModel->getByUsername($username);
            echo json_encode($userData);
            exit;
        }
    }
 
    public function loginGithub() {

        
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
        
            // Intercambiar el code por un access token
            $token_url = "https://github.com/login/oauth/access_token";
        
            $data = [
                "client_id" => Config::GITHUB_CLIENT_ID,
                "client_secret" => Config::GITHUB_CLIENT_SECRET,
                "code" => $code
            ];
        
            $options = [
                "http" => [
                    "header" => "Content-type: application/json\r\nAccept: application/json\r\n",
                    "method" => "POST",
                    "content" => json_encode($data),
                ]
            ];
            $context = stream_context_create($options);
            $result = file_get_contents($token_url, false, $context);
            $response = json_decode($result, true);
        
            $access_token = $response['access_token'];

            if (!$access_token) {
                die("Error al obtener el token de acceso.");
            }
        
            // Obtener los datos del usuario
            $user_info = file_get_contents("https://api.github.com/user", false, stream_context_create([
                "http" => [
                    "header" => "User-Agent: Techie\r\nAuthorization: token $access_token\r\n"
                ]
            ]));
        
            $user = json_decode($user_info, true);
        
            // Aquí puedes iniciar sesión, guardar en BD, etc.
            $github_id = $user['id'];
            $username = $user['login'];
            $email = $user['email'] ?? null;
            $name = $user['name'] ?? null;
            $last_name = null;
            $avatar_url = $user['avatar_url'] ?? null;
            
            if(!$email) {
                $emails_response = file_get_contents("https://api.github.com/user/emails", false, stream_context_create([
                    "http" => [
                        "header" => "User-Agent: Techie\r\nAuthorization: token $access_token\r\n"
                    ]
                ]));
                
                $emails = json_decode($emails_response, true);
                foreach ($emails as $e) {
                    if ($e['primary'] && $e['verified']) {
                        $email = $e['email'];
                        break;
                    }
                }
            }

            if (!$email) {
                die("No se pudo obtener el correo electrónico del usuario.");
            }

            // Verificar si ya existe
            $existingUser = $this->userModel->githubExists($github_id);

            if (!$existingUser) {
                $this->userModel->githubLogin($github_id, $username, $email, $name, $last_name, $avatar_url);
            } else {
                $_SESSION['user'] = [
                    'id' => $existingUser['id'],
                    'username' => $existingUser['username'],
                    'email' => $existingUser['email'],
                    'github_id' => $existingUser['github_id'],
                    'access_token' => $access_token,
                    'avatar_url' => $existingUser['avatar_url']
                ];
            }

            header('Location: ' . Config::PATH . 'home');
        } else {
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'access_denied') {
                    header('Location: ' . Config::PATH . 'login');
                }
                die("Error: " . $_GET['error']);
            }
            die("No se recibió el código de autorización.");
        }
    }

    public function getGithubRepoCount() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }
        if (!isset($_SESSION['user']['access_token'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No access token provided.']);
            exit();
        }
        $access_token = $_SESSION['user']['access_token'];

        $response = file_get_contents("https://api.github.com/user", false, stream_context_create([
            "http" => [
                "header" => "User-Agent: Techie\r\nAuthorization: token $access_token\r\n"
            ]
        ]));
        $response = json_decode($response, true);
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function getFollowStats($userId = null) {
        if ($userId == null && isset($_GET['parametro'])) {
            $userId = $_GET['parametro'];
        }

        $response = $this->userModel->getFollowStats($userId);
        echo json_encode($response);
        exit;
    }
}