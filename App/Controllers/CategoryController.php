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
            $icon = $_POST['icon'];

            $data = [
                'title' => $title,
                'icon' => $icon,
            ];
            
            $this->categoryModel->create($data);
            echo json_encode(['status' => 'success', 'message' => 'Categoría creada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método incorrecto.']);
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"), $_PUT);
            $id = $_PUT['id'];
            $title = $_PUT['title'];
            $icon = $_PUT['icon'];

            $data = [
                'title' => $title,
                'icon' => $icon,
            ];
            
            $this->categoryModel->update($data, $id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método incorrecto.']);
        }
    }
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            parse_str(file_get_contents("php://input"), $_DELETE);
            $id = $_DELETE['id'];
            
            $this->categoryModel->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría eliminada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método incorrecto.']);
        }
    }
}