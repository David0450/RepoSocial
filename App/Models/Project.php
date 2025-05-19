<?php

class Project extends EmptyModel {
	/*private $members = parent::__construct('project_members', 'project_id');
	private $tags = parent::__construct('project_tags', 'project_id');
	private $comments = parent::__construct('project_comments', 'project_id');*/

    public function __construct() {
        parent::__construct('projects');
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

	public function getStoredProjects($offset = 0, $limit = 10) {
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

}