<?php

namespace App\Controllers;
use App\Models\User;
use App\Core\Config;
use App\Core\Security;
use App\Controllers\MainController;
use Random\Engine\Secure;

class UserController extends MainController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }


    /*public function login() {
        if ($this->userModel->login()) {
            $_POST['uri'] = '';
            $_GET['uri'] = '';
            header('Location: ' . Config::PATH . 'hub');
        } else {
            header('Location: ' . Config::PATH . 'login');
        }
    }*/

    public function delete() {
        if (!Security::isLoggedIn()) {
            header('Location: ' . Config::PATH . 'login');
            exit();
        }

        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->userModel->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado.']);
        }
        exit();
    }

    public function update() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'/hub');
            exit;
        }
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['name'];
            $lastName = $_POST['last_name'];
            $avatarUrl = $_POST['avatar_url'];
            $githubId = $_POST['github_id'];

            $data = [
                'username' => $username,
                'email' => $email,
                'name' => $name,
                'last_name' => $lastName,
                'avatar_url' => $avatarUrl,
                'github_id' => $githubId,
            ];
            
            $this->userModel->update($data, $id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada.']);
        }
    }

    public function getAll() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getAll($offset, $limit);
        echo json_encode($users);
        exit;
    }

    public function logout() {
        
        $this->userModel->logout();
        $_POST['uri'] = '';
        $_GET['uri'] = '';
        header('Location: ' . Config::PATH . 'hub');
    }

    public function account() {
        if (!isset($_GET['parametro'])) {
            $user = $_SESSION['user'];
        } else {
            $user = $this->userModel->getByUsername($_GET['parametro']);
            if (!$user) {
                header('Location: ' . Config::PATH . 'hub');
                exit();
            }
        }

        $this->renderAccount($user);
    }

    public function profile() {
        if (!isset($_GET['parametro'])) {
            $user = $_SESSION['user'];
        } else {
            $user = $this->userModel->getByUsername($_GET['parametro']);
            if (!$user) {
                header('Location: ' . Config::PATH . 'hub');
                exit();
            }
        }

        $this->renderProfile($user);
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
                    "header" => "User-Agent: RepoSocial\r\nAuthorization: token $access_token\r\n"
                ]
            ]));
        
            $user = json_decode($user_info, true);
        
            $github_id = $user['id'];
            $username = $user['login'];
            $email = $user['email'] ?? null;
            $name = $user['name'] ?? null;
            $last_name = null;
            $avatar_url = $user['avatar_url'] ?? null;
            
            if(!$email) {
                $emails_response = file_get_contents("https://api.github.com/user/emails", false, stream_context_create([
                    "http" => [
                        "header" => "User-Agent: RepoSocial\r\nAuthorization: token $access_token\r\n"
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

            // Verificar si ya existe
            $existingUser = $this->userModel->githubExists($github_id);

            if (!$existingUser) {
                $this->userModel->githubLogin($github_id, $username, $email, $name, $last_name, $avatar_url, $access_token);
            } else {
                $user = $this->userModel->getByGithubId($github_id);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'github_id' => $user['github_id'],
                    'avatar_url' => $user['avatar_url'],
                    'role' => $user['role'],
                    'access_token' => $access_token,
                ];
            }

            header('Location: ' . Config::PATH . 'hub');
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
                "header" => "User-Agent: RepoSocial\r\nAuthorization: token $access_token\r\n"
            ]
        ]));
        $response = json_decode($response, true);
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function getFollowStats($username = null) {
        if ($username == null && isset($_GET['parametro'])) {
            $username = $_GET['parametro'];
        }

        $response = $this->userModel->getFollowStats($username);
        echo json_encode($response);
        exit;
    }

    public function follow() {
        if (isset($_POST['followerId'])) {
            $followerId = $_POST['followerId'];
        }
        if (isset($_POST['followedId'])) {
            $followedId = $_POST['followedId'];
        }

        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }
        if (!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No estás logeado.']);
            exit();
        }

        $response = $this->userModel->follow($followerId, $followedId);
        if ($response) {
            echo json_encode(['status' => 'success', 'message' => 'Usuario seguido.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al seguir al usuario.']);
        }
    }
    
    public function getUsersCount() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }

        $count = $this->userModel->getUsersCount();
        echo json_encode(['count' => $count]);
        exit();
    }

    public function editUsername() {
        if (!Security::isLoggedIn() || $_GET['parametro'] !== $_SESSION['user']['username']) {
            exit();
        }
        $newUsername = $_POST['newUsername'];
        $response = $this->userModel->editUsername($newUsername, $_SESSION['user']['username']);
        echo json_encode(['success' => $response]);
        return;
    }

    public function uploadProfileImage() {
        // Verificar si el usuario está autenticado
        if (!Security::isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autorizado.']);
            exit();
        }

        // Validar datos recibidos
        if (!isset($_GET['parametro']) || !isset($_FILES['imagen'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes.']);
            exit();
        }

        $username = $_GET['parametro'];
        $imagenData = $_FILES['imagen'];

        // Validar el archivo subido
        if ($imagenData['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
            exit();
        }

        // Validar tipo de imagen
        $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $fileType = mime_content_type($imagenData['tmp_name']);
        if (!array_key_exists($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de imagen no permitido.']);
            exit();
        }
        $type = $allowedTypes[$fileType];

        // Guardar la imagen en el servidor
        $uploadDir = __DIR__ . '/../../Public/assets/images/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filename = $username . '_' . uniqid() . '.' . $type;
        $filePath = $uploadDir . $filename;

        if (!move_uploaded_file($imagenData['tmp_name'], $filePath)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar la imagen.']);
            exit();
        }

        // Ruta accesible públicamente
        $publicPath = 'Public/assets/images/profiles/' . $filename;

        // Obtener el usuario
        $user = $this->userModel->getByUsername($username);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            exit();
        }

        // Actualizar la imagen en la base de datos
        $update = $this->userModel->updateProfileImage($user['id'], $publicPath);

        if ($update) {
            echo json_encode(['success' => true, 'avatar_url' => $publicPath]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la imagen en la base de datos.']);
        }
        exit();
    }
}