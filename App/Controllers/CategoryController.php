<?php 

require __DIR__ . '/../Models/Category.php';
require __DIR__ . '/../Models/Project.php';

class CategoryController {
    
    private $categoryModel;
    private $projectModel;
    
    public function __construct() {
        $this->categoryModel = new Category();
        $this->projectModel = new Project();
    }
    
    public function getAll() {
        $categories = $this->categoryModel->getAll();
        if (empty($categories)) {
            echo json_encode(['status' => 'error', 'message' => 'No hay categorías.']);
            exit();
        }
        foreach ($categories as $key => $category) {
            $projects = $this->projectModel->getByCategoryId($category['id']);
            $categories[$key]['projects'] = $projects[0]['projects'];
        }
        echo json_encode($categories);
        exit();
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            
            $this->categoryModel->create($title);
            echo json_encode(['status' => 'success', 'message' => 'Categoría creada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método incorrecto.']);
        }
    }
}