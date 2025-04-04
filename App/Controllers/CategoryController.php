<?php 

require __DIR__ . '/../Models/Category.php';

class CategoryController {
    
    private $categoryModel;
    
    public function __construct() {
        $this->categoryModel = new Category();
    }
    
    public function getAll() {
        $categories = $this->categoryModel->getAll();
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