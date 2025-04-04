<?php

class User extends EmptyModel {
    private $roles = parent::__construct('user_roles', 'user_id');
    private $chats = parent::__construct('chat_members', 'user_id'); 
    private $projects = parent::__construct('projects', 'posted_by');
    private $followers = parent::__construct('user_follows', 'followed_user_id');
    private $following = parent::__construct('user_follows', 'following_user_id');
    
    public function __construct() {
        parent::__construct('users');
    }
}