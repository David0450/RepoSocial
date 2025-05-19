<?php

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/Category.php';

class ProjectController {
    private $projectModel;
    private $categoryModel;
    private $commentModel;

    public function __construct() {
        $this->projectModel = new Project();
        $this->categoryModel = new Category();
        //$this->commentModel = new Comment();
    }

    public function index() {
        // Logic to display all projects
        //$projects = $this->projectModel->getAll();
        include __DIR__ . '/../Views/projects/projects_list.php';
        exit();
    }

    public function show($id) {
        // Logic to display a single project
        $project = $this->projectModel->getById($id);
        include __DIR__ . '/../Views/projects/show.php';
    }
    public function create() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'/login');
            exit();
        }
        // Logic to create a new project
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $this->projectModel->create($data);
            header('Location: /projects');
            exit();
        } else {
            $categories = $this->categoryModel->getAll();
            include __DIR__ . '/../Views/projects/projects_create.php';
        }
    }

    public function userProjects() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }
        
        include_once __DIR__ . '/../Views/projects/projects_create.php';
    }


    public function getUserProjects() {

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

        $response = file_get_contents("https://api.github.com/user/repos?visibility=all&affiliation=owner&per_page=100", false, stream_context_create([
            "http" => [
                "header" => "User-Agent: Techie\r\nAuthorization: token $access_token\r\n"
            ]
        ]));

        if ($response === false) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error fetching repositories.']);
            exit();
        }

        $total_repos = count(json_decode($response, true));

        header('Content-Type: application/json');
        echo json_encode(['repos' => $response, 'total' => $total_repos]);
        exit();
    }

    public function getUserProjectsById($id) {
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

        $response = file_get_contents("https://api.github.com/user/repos?visibility=all&affiliation=owner&per_page=100", false, stream_context_create([
            "http" => [
                "header" => "User-Agent: Techie\r\nAuthorization: token $access_token\r\n"
            ]
        ]));

        if ($response === false) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error fetching repositories.']);
            exit();
        }

        $repos = json_decode($response, true);

        foreach ($repos as $repo) {
            if ($repo['id'] == $id) {
                header('Content-Type: application/json');
                return json_encode($repo);
            }
        }

        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Repository not found.']);
        exit();
    }

    public function uploadUserProjects() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $repoId = $_GET['repo_id'] ?? null;
            if (!$repoId) {
                echo "No repository ID provided.";
                return;
            }
            $repoData = json_decode($this->getUserProjectsById($repoId),true);
            if (!$repoData) {
                echo "No repository data found.";
                return;
            }
            
            $data = [
                'id' => $repoData['id'],
                'user_id' => $_SESSION['user']['id'],
                'title' => $repoData['name'],
                'description' => $repoData['description'] ?? null,
                'html_url' => $repoData['html_url'],
                'private' => $repoData['private'] ? 1 : 0,
                'uploaded_at' => date('Y-m-d H:i:s'),
                'status' => 'private',
                'owner_avatar' => $repoData['owner']['avatar_url'],
                'category_id' => null,
            ];

            $this->projectModel->create($data);
            header('Location: ' . Config::PATH . 'home');
            exit();
        }
    }

    public function getUploadedProjectById($id = null) {
        if (!$id) {
            $id = $_GET['repo_id'];
        }

        $uploadedRepo = $this->projectModel->getById($id);

        echo json_encode($uploadedRepo);
    }

    public function getAllUserUploadedProjects($userId = null) {
        if ($userId == null && isset($_GET['parametro'])) {
            $userId = $_GET['parametro'];
        }

        $response = $this->projectModel->getByUserId($userId, 0, 100);
        echo json_encode($response);
        exit;
    }
}
