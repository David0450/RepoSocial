<?php
session_start();

//require __DIR__ . '/autoload.php';

require __DIR__ . '/Core/Router.php';
require __DIR__ . '/Core/Database.php';
require __DIR__ . '/Core/EmptyModel.php';
require __DIR__ . '/Core/Config.php';

/*
require __DIR__ . '/Models/User.php';
require __DIR__ . '/Models/Chat.php';
require __DIR__ . '/Models/Project.php';
*/

require __DIR__ . '/Controllers/CategoryController.php';
require __DIR__ . '/Controllers/MainController.php';
require __DIR__ . '/Controllers/ProjectController.php';

$router = new Router();

$router->add('/', 'ProjectController@index', 'GET');
$router->add('/home', 'ProjectController@index', 'GET');
$router->add('/login', 'MainController@login');

$router->add('/categories', 'CategoryController@getAll', 'GET');
$router->add('/categories',  'CategoryController@create', 'POST');
$router->add('/categories',  'CategoryController@update', 'PUT');
$router->add('/categories',  'CategoryController@delete', 'DELETE');

$router->add('/tags', 'TagController@getAll', 'GET');
$router->add('/tags', 'TagController@create', 'POST');
$router->add('/tags', 'TagController@update', 'PUT');
$router->add('/tags', 'TagController@delete', 'DELETE');

$router->add('/user/login', 'MainController@login', 'POST');
$router->add('/user/logout', 'MainController@logout', 'GET');

$router->run();

include __DIR__ . '/Views/mainView.php'; 
?>