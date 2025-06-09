<?php
session_start();
date_default_timezone_set('Europe/Madrid');

require __DIR__ . '/../vendor/autoload.php';


use App\Core\Router;
use App\Core\Security;
use App\Core\Config;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controllers\ChatController;
use App\Controllers\MainController;
use App\Controllers\ProjectController;
use App\Controllers\UserController;
use App\Controllers\CategoryController;
use App\Controllers\TagController;
use App\Controllers\AdminController;
use App\Controllers\SearchController;

$router = new App\Core\Router();

$router->add('/', 'MainController@renderLanding', 'GET');
$router->add('/home', 'MainController@renderLanding', 'GET');
/*$router->add('/login', 'MainController@renderLogin');
$router->add('/user/login', 'UserController@login', 'POST');*/


$router->add('/categories', 'CategoryController@getAll', 'GET');
$router->add('/categories',  'CategoryController@create', 'POST');
$router->add('/categories/update',  'CategoryController@update', 'POST');
$router->add('/categories/delete',  'CategoryController@delete', 'POST');

$router->add('/tags', 'TagController@getAll', 'GET');
$router->add('/tags', 'TagController@create', 'POST');
$router->add('/tags/update', 'TagController@update', 'POST');
$router->add('/tags/delete', 'TagController@delete', 'POST');

$router->add('/user/logout', 'UserController@logout', 'GET');

$router->add('/{username}/account', 'UserController@account', 'GET');
$router->add('/{username}', 'UserController@profile', 'GET');

$router->add('/users/{username}/github-repos', 'ProjectController@getUserGithubRepos');
$router->add('/users/{username}/github-repos/view', 'ProjectController@showGithubReposView');
$router->add('/users/{username}/github-repos/import', 'ProjectController@importGithubRepo');

$router->add('/users/{username}/projects', 'ProjectController@getStoredUserProjects');
$router->add('/projects/getById', 'ProjectController@getStoredProjectById');
$router->add('/projects', 'ProjectController@getStoredProjects', 'POST');
$router->add('/projects/{category}', 'ProjectController@getByCategory');
$router->add('/projects/{tag}', 'ProjectController@getByTag');
$router->add('/hub', 'ProjectController@index');
$router->add('/projects/count', 'ProjectController@getProjectsCount', 'GET');
$router->add('/projects/delete', 'ProjectController@delete', 'POST');
$router->add('/projects/addLike', 'ProjectController@addLike', 'POST');
$router->add('/projects/removeLike', 'ProjectController@removeLike', 'POST');
$router->add('/projects/update', 'ProjectController@update', 'POST');

$router->add('/users/{username}/github/repos/count', 'UserController@getGithubRepoCount');
$router->add('/users/{username}/data', 'UserController@getStoredUserByUsername');
$router->add('/users/{username}/follow-stats', 'UserController@getFollowStats');
$router->add('/users/count', 'UserController@getUsersCount');
$router->add('/users/delete', 'UserController@delete', 'POST');
$router->add('/users/update', 'UserController@update', 'POST');
$router->add('/users/{username}/edit-username', 'UserController@editUsername', 'POST');
$router->add('/users/{username}/upload-profile-image', 'UserController@uploadProfileImage', 'POST');

$router->add('/users/{username}/follow', 'UserController@follow', 'POST');

$router->add('/github-callback', 'UserController@loginGithub', 'GET');
$router->add('/project/tags', 'TagController@getTagsByProject', 'GET');

$router->add('/chats', 'ChatController@mostrarVistaChat', 'GET');
$router->add('/getChats', 'ChatController@obtenerListaChatsJson', 'GET');
$router->add('/chats/new/{username}', 'ChatController@crearChatConUsuario', 'GET');
$router->add('/chats/{chat_id}/messages', 'ChatController@obtenerMensajes', 'GET');
$router->add('/chats/{chat_id}/avatar', 'ChatController@obtenerAvatar', 'GET');

$router->add('/admin', 'AdminController@index', 'GET');

$router->add('/admin/projects', 'ProjectController@getAll', 'GET');
$router->add('/admin/users', 'UserController@getAll', 'GET');
$router->add('/admin/categories', 'CategoryController@getAll', 'GET');
$router->add('/admin/tags', 'TagController@getAll', 'GET');

$router->add('/search', 'SearchController@search', 'GET');

$router->run();


?>