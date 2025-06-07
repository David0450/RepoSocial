<?php 

namespace App\Controllers;
use App\Models\Tag;

class TagController {
    
    private $tagModel;
    
    public function __construct() {
        $this->tagModel = new Tag();
    }
    
    public function getAll() {
        $tags = $this->tagModel->getAll();
        foreach ($tags as $key => $tag) {
            $projects = $this->tagModel->getCountProjects($tag['id']);
            $tags[$key]['projects'] = $projects;
        }
        echo json_encode($tags);
        exit();
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
            $title = $_POST['title'];

            $data = [
                'title' => $title,
            ];
            
            $this->tagModel->create($data);
            echo json_encode(['status' => 'success', 'message' => 'Etiqueta creada.']);
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
            
            $this->tagModel->update($data, $id);
            echo json_encode(['status' => 'success', 'message' => 'Etiqueta actualizada.']);
    }

    public function delete() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->tagModel->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Etiqueta eliminada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de etiqueta no proporcionado.']);
        }
    }

    public function getTagsByProject() {
        $projectId = $_GET['project_id'];

        $tags = $this->tagModel->getTagsByProject($projectId);
        echo json_encode($tags);
        exit();
    }
}