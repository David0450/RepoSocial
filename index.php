<?php
session_start();
date_default_timezone_set('Europe/Madrid');

//require __DIR__ . '/autoload.php';

require __DIR__ . '/App/Core/Router.php';
require __DIR__ . '/App/Core/Database.php';
require __DIR__ . '/App/Core/EmptyModel.php';
require __DIR__ . '/App/Core/Config.php';
require __DIR__ . '/App/Core/Security.php';

/*
require __DIR__ . '/Models/User.php';
require __DIR__ . '/Models/Chat.php';
require __DIR__ . '/Models/Project.php';
*/

require __DIR__ . '/App/Controllers/CategoryController.php';
require __DIR__ . '/App/Controllers/MainController.php';
require __DIR__ . '/App/Controllers/ProjectController.php';
require __DIR__ . '/App/Controllers/UserController.php';

$router = new Router();

$router->add('/', function() {
    header('Location: '. Config::PATH .'home');
    exit();
});
$router->add('/home', 'ProjectController@index', 'GET');
$router->add('/login', 'MainController@login');
$router->add('/signup', 'MainController@signup');

$router->add('/categories', 'CategoryController@getAll', 'GET');
$router->add('/categories',  'CategoryController@create', 'POST');
$router->add('/categories',  'CategoryController@update', 'PUT');
$router->add('/categories',  'CategoryController@delete', 'DELETE');

$router->add('/tags', 'TagController@getAll', 'GET');
$router->add('/tags', 'TagController@create', 'POST');
$router->add('/tags', 'TagController@update', 'PUT');
$router->add('/tags', 'TagController@delete', 'DELETE');

$router->add('/user/login', 'UserController@login', 'POST');
$router->add('/user/logout', 'UserController@logout', 'GET');
$router->add('/user/signup', 'UserController@signup', 'POST');
$router->add('/user/account', 'UserController@account', 'GET');
$router->add('/user/login/google', 'UserController@loginGoogle', 'GET');

$router->add('/user/projects/post', 'ProjectController@insertUserProjects');
$router->add('/user/projects/count', 'UserController@getTotalRepos', 'GET');
$router->add('/user/projects/upload', 'ProjectController@uploadUserProjects');

$router->add('/user/projects', 'ProjectController@userProjects', 'GET');
$router->add('/user/projects/get', 'ProjectController@getUserProjects', 'GET');
$router->add('/user/projects/getUploaded', 'ProjectController@getUploadedProjectsById', 'GET');

$router->add('/projects/create', 'ProjectController@create');

$router->add('/github_callback', 'UserController@loginGithub', 'GET');

$router->run();


?>