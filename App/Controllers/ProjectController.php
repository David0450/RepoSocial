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
            header('Location: /login');
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

    public function insertUserProjects() {
        if (!Security::isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        $access_token = $_GET['token'] ?? null;
        if (!$access_token) {
            echo "No access token provided.";
            return;
        }
        $userId = $_SESSION['user']['id'];
        $repos_response = file_get_contents("https://api.github.com/user/repos?visibility=all&per_page=100", false, stream_context_create([
            "http" => [
                "header" => "User-Agent: tu-app\r\nAuthorization: token $access_token\r\n"
            ]
        ]));
        
        $repos = json_decode($repos_response, true);
        
        foreach ($repos as $repo) {
            $repo_name = $repo['name'];
            $repo_description = $repo['description'] ?? null;
            $repo_url = $repo['html_url'];
            $repo_id = $repo['id'];
            $repo_private = $repo['private'] ? 1 : 0;
            $repo_created_at = $repo['created_at'];
            
            // Check if the project already exists in the database
            if (!$this->projectModel->getById($repo_id)) {
                // Create a new project in the database
                $this->projectModel->create([
                    'id' => $repo_id,
                    'user_id' => $userId,
                    'title' => $repo_name,
                    'description' => $repo_description,
                    'html_url' => $repo_url,
                    'private' => $repo_private,
                    'created_at' => $repo_created_at,
                    'status' => 'private',
                ]);
            }
        }  
        
        header('Location: ' . Config::PATH . 'home');
    }

    public function getUserProjects() {
        if (!Security::isLoggedIn()) {
            header('Location: /login');
            exit();
        }
        $userId = $_SESSION['user']['id'];
        $projects = $this->projectModel->getByUserId($userId);
        echo json_encode($projects);
    }
}