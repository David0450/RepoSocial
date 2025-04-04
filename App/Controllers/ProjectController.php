<?php



class ProjectController {
    private $projectModel;
    private $categoryModel;
    private $commentModel;

    /*public function __construct() {
        $this->projectModel = new Project();
        $this->categoryModel = new Category();
        $this->commentModel = new Comment();
    }*/

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
        // Logic to create a new project
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $this->projectModel->create($data);
            header('Location: /projects');
            exit();
        } else {
            $categories = $this->categoryModel->getAll();
            include __DIR__ . '/../Views/projects/create.php';
        }
    }
}