<?php

class Comment extends EmptyModel {
    private $replies = parent::__construct('comment_subcomment', 'parent_id');
    
    
    public function __construct() {
        parent::__construct('comments');
    }   
}