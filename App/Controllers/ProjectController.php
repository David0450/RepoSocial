<?php

namespace App\Controllers;
use App\Core\Config;
use App\Models\Project;
use App\Core\Security;
use App\Controllers\MainController;

class ProjectController extends MainController{
    private $projectModel;

    public function __construct() {
        $this->projectModel = new Project();
    }

    public function index() {
        // Logic to display all projects
        //$projects = $this->projectModel->getAll();
        $this->renderProjects();
        exit();
    }

    public function delete() {
        if (!Security::isLoggedIn()) {
            header('Location: '.Config::PATH.'/hub');
            exit();
        }
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->projectModel->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Project deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Project ID not provided.']);
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
            $title = $_POST['title'];
            $private = $_POST['private'];
            $description = $_POST['description'];
            $categoryId = $_POST['category_id'];
            $userId = $_POST['user_id'];
            $uploadedAt = $_POST['uploaded_at'];
            $status = $_POST['status'];
            $htmlUrl = $_POST['html_url'];
            $ownerAvatar = $_POST['owner_avatar'];

            $data = [
                'title' => $title,
                'private' => $private,
                'description' => $description,
                'category_id' => $categoryId,
                'user_id' => $userId,
                'uploaded_at' => $uploadedAt,
                'status' => $status,
                'html_url' => $htmlUrl,
                'owner_avatar' => $ownerAvatar
            ];
            
            $this->projectModel->update($data, $id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada.']);
        }
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
        $this->renderProjectCreate();
        exit();
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
                "header" => "User-Agent: RepoSocial\r\nAuthorization: token $access_token\r\n"
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
                "header" => "User-Agent: RepoSocial\r\nAuthorization: token $access_token\r\n"
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

            header('Location: ' . Config::PATH . 'users/@'. $_SESSION['user']['username'] .'/github-repos/view');
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
        $data = json_decode(file_get_contents("php://input"), true);

        $offset = isset($data['offset']) ? intval($data['offset']) : 0;
        $limit = 10;

        $order = $data['order'];
        $direction = $data['direction'];

        $category = $data['category'];
        $tags = empty($data['tags']) ? null : $data['tags'];

        $projects = $this->projectModel->getStoredProjects($offset, $limit, $category, $tags, $order, $direction);

        // Para cada proyecto, obtener sus likes
        foreach ($projects as &$project) {
            $response = $this->getLikes(($project['id']));
            $project['like_count'] = $response['like_count'];
            $project['has_liked'] = $response['has_liked'];
        }

        echo json_encode($projects);
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

    public function getProjectsCount() {
        $count = $this->projectModel->getProjectsCount();
        echo json_encode(['count' => $count]);
        exit;
    }

    public function getAll() {
        $projects = $this->projectModel->getAll();
        echo json_encode($projects);
        exit;
    }

    private function getLikes($projectId) {
        $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
        $likes = $this->projectModel->getLikes($projectId, $userId);
        return $likes;
    }

    public function addLike() {
        $input = json_decode(file_get_contents('php://input'), true);
        $projectId = $input['project_id'];
        $userId = $_SESSION['user']['id'];

        $this->projectModel->addLike($projectId, $userId);
        echo json_encode(['status' => 'success', 'message' => 'Like added successfully.']);
        exit;
    }

    public function removeLike() {
        $input = json_decode(file_get_contents('php://input'), true);
        $projectId = $input['project_id'];
        $userId = $_SESSION['user']['id'];

        $this->projectModel->removeLike($projectId, $userId);
        echo json_encode(['status' => 'success', 'message' => 'Like removed successfully.']);
        exit;
    }
}
