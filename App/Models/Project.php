<?php

namespace App\Models;
use App\Core\EmptyModel;
use PDO;

class Project extends EmptyModel {
	public $id;
	public $title;
	public $languages;
	public $ownerUsername;

    public function __construct() {
        parent::__construct('projects');
    }   

	public function import($data, $owner) {
		$this->create($data);
		$this->id = $data['id'];
		$this->title = $data['title'];
		$this->ownerUsername = $owner;

		$languages = $this->getProjectLanguages();
		
		if ($languages != false) {
			$this->languages = array_map(function($key) {
				return "'#" . $key . "'";
			}, array_keys($languages));
			var_dump($this->languages);

			// Obtener tags existentes con esos nombres
			$placeholders = implode(',', $this->languages);
			$tags = $this->db->query("SELECT id, title FROM tags WHERE LOWER(title) IN ($placeholders)", PDO::FETCH_ASSOC);
	
			// Insertar en la tabla intermedia
			$stmtInsert = $this->db->prepare("INSERT INTO project_tags (project_id, tag_id) VALUES (?, ?)");
			foreach ($tags as $tag) {
				$stmtInsert->execute([$this->id, $tag['id']]);
			}
		}
	}

	public function getByCategoryId($categoryId) {
		$sql = "SELECT COUNT(*) AS projects FROM {$this->table} WHERE category_id = :category_id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserId($userId,$offset = 0, $limit = 6) {
		$sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT :offset, :limit";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$totalSql = "SELECT FOUND_ROWS() AS total";
		$totalStmt = $this->db->query($totalSql);
		$total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

		return ['repos' => $results, 'total' => $total];
	}

	public function getStoredProjects($offset = 0, $limit = 12) {
		$sql = "SELECT projects.*, 
					users.username, users.avatar_url,
					categories.title AS category_name, 
					GROUP_CONCAT(tags.title) AS tags 
				FROM projects 
				JOIN users ON projects.user_id = users.id 
				LEFT JOIN categories ON projects.category_id = categories.id 
				LEFT JOIN project_tags ON projects.id = project_tags.project_id 
				LEFT JOIN tags ON project_tags.tag_id = tags.id 
				GROUP BY projects.id 
				ORDER BY projects.uploaded_at DESC 
				LIMIT :limit OFFSET :offset";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getProjectLanguages() {
		$url = "https://api.github.com/repos/{$this->ownerUsername}/{$this->title}/languages";
		$access_token = $_SESSION['user']['access_token'];

		$options = [
			'http' => [
                "header" => "User-Agent: Techie\r\nAuthorization: token $access_token\r\n"
			]
		];
		
		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);
		
		if ($response !== false) {
			$languages = json_decode($response, true);
			return $languages;
		} else {
			return false;
		}
	}

	public function getByCategory($categoryId) {
		$limit = 12;
		$offset = 0;
		$sql = "SELECT projects.*, 
					users.username, users.avatar_url,
					categories.title AS category_name, 
					GROUP_CONCAT(tags.title) AS tags 
				FROM {$this->table} 
				JOIN users ON projects.user_id = users.id 
				LEFT JOIN categories ON projects.category_id = categories.id 
				LEFT JOIN project_tags ON projects.id = project_tags.project_id 
				LEFT JOIN tags ON project_tags.tag_id = tags.id 
				WHERE projects.category_id = :category_id
				GROUP BY projects.id 
				ORDER BY projects.uploaded_at DESC 
				LIMIT :limit OFFSET :offset";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(":category_id", $categoryId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByTag($tagId) {
		$limit = 12;
		$offset = 0;
		$sql = "SELECT projects.*, 
					users.username, users.avatar_url,
					categories.title AS category_name, 
					GROUP_CONCAT(tags.title) AS tags 
				FROM {$this->table} 
				JOIN users ON projects.user_id = users.id 
				LEFT JOIN categories ON projects.category_id = categories.id 
				LEFT JOIN project_tags ON projects.id = project_tags.project_id 
				LEFT JOIN tags ON project_tags.tag_id = tags.id 
				WHERE project_tags.tag_id = :tag_id
				GROUP BY projects.id 
				ORDER BY projects.uploaded_at DESC 
				LIMIT :limit OFFSET :offset";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(":tag_id", $tagId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}