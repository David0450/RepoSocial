<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Project;

class SearchController {
    private $userModel;
    private $projectModel;

    public function __construct() {
        $this->userModel = new User();
        $this->projectModel = new Project();
    }

    public function search() {
        if (!isset($_GET['query']) || strlen(trim($_GET['query'])) === 0) {
            echo json_encode(['users' => [], 'projects' => []]);
            return;
        }

        $query = trim($_GET['query']);
        $users = $this->userModel->searchUsersByPrefix($query);
        $projects = $this->projectModel->searchProjectsByPrefix($query);

        header('Content-Type: application/json');
        echo json_encode(['users' => $users, 'projects' => $projects]);
    }
}
