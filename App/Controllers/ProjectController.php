<?php

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/Category.php';

class ProjectController {
    private $projectModel;

    public function __construct() {
        $this->projectModel = new Project();
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
            include __DIR__ . '/../Views/projects/projects_create.php';
        }
    }

    public function showGithubReposView() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }
        
        include_once __DIR__ . '/../Views/projects/projects_create.php';
    }


    public function getUserGithubRepos() {

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

        $response = file_get_contents("https://api.github.com/user/repos?visibility=public&affiliation=owner&per_page=100", false, stream_context_create([
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

        $response = file_get_contents("https://api.github.com/user/repos?visibility=public&affiliation=owner&per_page=100", false, stream_context_create([
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

    public function importGithubRepo() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $repoId = $_GET['repo_id'] ?? null;
            $categoryId = $_GET['categorie'] ?? null;
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
                'category_id' => $categoryId,
            ];

            $this->projectModel->import($data, $_GET['parametro']);

            header('Location: ' . Config::PATH . 'home');
            exit();
        }
    }

    public function getStoredProjectById($id = null) {
        if ($id == null) {
            $id = $_GET['repo_id'];
        }

        $uploadedRepo = $this->projectModel->getById($id);

        echo json_encode($uploadedRepo);
    }

    public function getStoredUserProjects($userId = null) {
        $userId = $_GET['userId'];
        $response = $this->projectModel->getByUserId($userId, 0, 100);
        echo json_encode($response);
        exit;
    }

    public function getStoredProjects() {
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
		$limit = 10;

        $response = $this->projectModel->getStoredProjects($offset, $limit);
        echo json_encode($response);
        exit;
    }

    public function getLanguages() {
        $languages = $this->projectModel->getProjectLanguages();
        print_r(array_keys($languages));
    }

    public function getByCategory() {
        $categoryId = isset($_GET['category']) ? $_GET['category'] : null;
        
        if ($categoryId) {
            $projects = $this->projectModel->getByCategory($categoryId);
            echo json_encode($projects);
        }
        exit;
    }

    public function getByTag() {
        $tagId = isset($_GET['tag']) ? $_GET['tag'] : null;
        
        if ($tagId) {
            $projects = $this->projectModel->getByTag($tagId);
            echo json_encode($projects);
        }
        exit;
    }
}
