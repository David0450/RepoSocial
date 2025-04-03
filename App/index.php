<?php
session_start();

require __DIR__ . '/autoload.php';

//require __DIR__ . '/Core/Router.php';
//require __DIR__ . '/Core/Database.php';
//require __DIR__ . '/Core/EmptyModel.php';

//require __DIR__ . '/Models/User.php';
//require __DIR__ . '/Models/Chat.php';
//require __DIR__ . '/Models/Project.php';

$router = new Router();


include __DIR__ . '/Views/mainView.php'; 
?>