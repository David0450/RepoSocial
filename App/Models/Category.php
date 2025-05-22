<?php 

namespace App\Models;
use App\Core\EmptyModel;

class Category extends EmptyModel {
    public function __construct() {
        parent::__construct('categories');
    }
}