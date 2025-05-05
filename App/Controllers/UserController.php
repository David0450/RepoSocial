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

    public function register() {
        // Logic for registration
        echo "Register page";
    }

    public function profile() {
        include __DIR__ . '/../Views/profile/profile_view.php';
    }

    public function loginGithub() {
        $client_id = 'Ov23lijzgiVY3WIWZerl';
        $client_secret = '37676bfac8d1b856c112fb634289ac7cfc4307f7';
        
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
        
            // Intercambiar el code por un access token
            $token_url = "https://github.com/login/oauth/access_token";
        
            $data = [
                "client_id" => $client_id,
                "client_secret" => $client_secret,
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
                    "header" => "User-Agent: tu-app\r\nAuthorization: token $access_token\r\n"
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
                        "header" => "User-Agent: tu-app\r\nAuthorization: token $access_token\r\n"
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
                    'github_id' => $existingUser['github_id']
                ];
            }

            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'http://localhost/programacion/PFC/App/user/projects', true);
                    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                try {
                                    var data = JSON.parse(xhr.responseText);
                                    console.log(data); // Debugging line to check the response
                                } catch (e) {
                                    console.error('Error parsing JSON response:', e);
                                }
                            } else {
                                console.error('Error fetching projects');
                            }
                        }
                    };
                    xhr.send(JSON.stringify({
                        'token': '<?= $access_token; ?>'
                    }));
                });
            </script>
            <?php
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
}