<?php
session_start();

//require __DIR__ . '/../autoload.php';

require __DIR__ . '/Core/Router.php';
require __DIR__ . '/Core/Database.php';
require __DIR__ . '/Core/EmptyModel.php';

$router = new Router();

$router->run();

include __DIR__ . '/Views/mainView.php'; 
?>