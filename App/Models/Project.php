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
		$sql = "
			SELECT 
				projects.*, 
				COUNT(DISTINCT project_likes.user_id) AS like_count,
				COUNT(DISTINCT project_comments.comment_id) AS comment_count
			FROM {$this->table} AS projects
			LEFT JOIN project_likes ON projects.id = project_likes.project_id
			LEFT JOIN project_comments ON projects.id = project_comments.project_id
			WHERE projects.user_id = :user_id
			GROUP BY projects.id
			LIMIT :offset, :limit
		";
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

	public function getStoredProjects($offset = 0, $limit = 12, $category = null, $tags = null, $order = null, $direction = 'DESC') {
		$params = [
			':limit' => $limit,
			':offset' => $offset
		];

		$withClause = "";
		$where = [];

		if ($tags !== null && is_array($tags) && count($tags) > 0) {
			$tagCount = count($tags);
			$placeholders = [];

			foreach ($tags as $i => $tag) {
				$placeholder = ":tag{$i}";
				$placeholders[] = $placeholder;
				$params[$placeholder] = $tag;
			}

			$withClause = "
				WITH projects_with_tags AS (
					SELECT project_id
					FROM project_tags
					WHERE tag_id IN (" . implode(',', $placeholders) . ")
					GROUP BY project_id
					HAVING COUNT(DISTINCT tag_id) = $tagCount
				)
			";

			$where[] = "projects.id IN (SELECT project_id FROM projects_with_tags)";
		}

		if ($category !== null) {
			$where[] = "projects.category_id = :category";
			$params[':category'] = $category;
		}

		// Validate direction
		$direction = strtoupper($direction);
		if (!in_array($direction, ['ASC', 'DESC'])) {
			$direction = 'DESC';
		}

		$orderBy = "ORDER BY projects.uploaded_at $direction";

		if ($order === 'likes') {
			$orderBy = "ORDER BY like_count $direction, projects.uploaded_at $direction";
		}

		$sql = "
			$withClause
			SELECT projects.*, 
					users.username, 
					users.avatar_url,
					categories.title AS category_name, 
					GROUP_CONCAT(tags.title) AS tags,
					COUNT(DISTINCT project_likes.user_id) AS like_count,
					COUNT(DISTINCT project_comments.comment_id) AS comment_count
			FROM projects 
			JOIN users ON projects.user_id = users.id 
			LEFT JOIN categories ON projects.category_id = categories.id 
			LEFT JOIN project_tags ON projects.id = project_tags.project_id 
			LEFT JOIN tags ON project_tags.tag_id = tags.id 
			LEFT JOIN project_likes ON projects.id = project_likes.project_id
			LEFT JOIN project_comments ON projects.id = project_comments.project_id
		";

		if (count($where) > 0) {
			$sql .= "WHERE " . implode(' AND ', $where) . " ";
		}

		$sql .= " GROUP BY projects.id ";
		$sql .= $orderBy;
		$sql .= " LIMIT :limit OFFSET :offset ";

		$stmt = $this->db->prepare($sql);

		foreach ($params as $key => $value) {
			$type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
			$stmt->bindValue($key, $value, $type);
		}

		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getProjectLanguages() {
		$url = "https://api.github.com/repos/{$this->ownerUsername}/{$this->title}/languages";
		$access_token = $_SESSION['user']['access_token'];

		$options = [
			'http' => [
                "header" => "User-Agent: RepoSocial\r\nAuthorization: token $access_token\r\n"
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

	public function getProjectsCount() {
		$sql = "SELECT COUNT(*) AS total FROM {$this->table}";
		$stmt = $this->db->query($sql);
		return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
	}

	public function searchProjectsByPrefix($prefix) {
    	$stmt = $this->db->prepare("SELECT title, html_url FROM projects WHERE title LIKE ? LIMIT 10");
    	$search = $prefix . '%';
    	$stmt->execute([$search]);
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLikes($projectId, $userId) {
		$sql = "SELECT 
					COUNT(*) AS like_count, 
					MAX(user_id = :user_id) AS has_liked 
				FROM project_likes 
				WHERE project_id = :project_id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return [
			'like_count' => $result ? (int)$result['like_count'] : 0,
			'has_liked' => $result ? (bool)$result['has_liked'] : false
		];
	}

	public function addLike($projectId, $userId) {
		$sql = "INSERT IGNORE INTO project_likes (project_id, user_id) VALUES (:project_id, :user_id)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function removeLike($projectId, $userId) {
		$sql = "DELETE FROM project_likes WHERE project_id = :project_id AND user_id = :user_id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		return $stmt->execute();
	}
}