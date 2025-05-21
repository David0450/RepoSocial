<?php 


class Tag extends EmptyModel {
    public function __construct() {
        parent::__construct('tags');
    }

    public function getCountProjects($tagId) {
		$sql = "SELECT COUNT(*) AS projects 
				FROM project_tags 
				WHERE tag_id = :tag_id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':tag_id', $tagId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTagsByProject($projectId) {
		$sql = "SELECT tags.title 
			FROM tags 
			INNER JOIN project_tags ON tags.id = project_tags.tag_id 
			WHERE project_tags.project_id = :project_id";

		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
}