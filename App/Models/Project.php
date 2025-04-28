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
}