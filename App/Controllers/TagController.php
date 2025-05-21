<?php 

require __DIR__ . '/../Models/Tag.php';

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
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"), $_PUT);
            $id = $_PUT['id'];
            $title = $_PUT['title'];
            $icon = $_PUT['icon'];

            $data = [
                'title' => $title,
                'icon' => $icon,
            ];
            
            $this->tagModel->update($data, $id);
            echo json_encode(['status' => 'success', 'message' => 'Etiqueta actualizada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método incorrecto.']);
        }
    }
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            parse_str(file_get_contents("php://input"), $_DELETE);
            $id = $_DELETE['id'];
            
            $this->tagModel->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Etiqueta eliminada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método incorrecto.']);
        }
    }

    public function getTagsByProject() {
        $projectId = $_GET['project_id'];

        $tags = $this->tagModel->getTagsByProject($projectId);
        echo json_encode($tags);
        exit();
    }
}