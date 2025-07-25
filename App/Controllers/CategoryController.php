<?php 

namespace App\Controllers;
use App\Models\Category;
use App\Models\Project;

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
            $id = $_POST['id'];
            $title = $_POST['title'];
            $icon = $_POST['icon'];

            $data = [
                'title' => $title,
                'icon' => $icon,
            ];
            
            $this->categoryModel->update($data, $id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada.']);
    }
    public function delete() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->categoryModel->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría eliminada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de categoría no proporcionado.']);
        }
    }
}