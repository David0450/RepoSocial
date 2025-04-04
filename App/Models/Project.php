<?php

class Project extends EmptyModel {
	private $members = parent::__construct('project_members', 'project_id');
	private $tags = parent::__construct('project_tags', 'project_id');
	private $comments = parent::__construct('project_comments', 'project_id');

    public function __construct() {
        parent::__construct('projects');
    }   
}