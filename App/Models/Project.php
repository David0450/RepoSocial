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

}