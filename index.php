<?php
declare(strict_types=1);

define('BASE_PATH',   __DIR__);
define('APP_PATH',    BASE_PATH . '/app');
define('CORE_PATH',   BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');

require_once CONFIG_PATH . '/config.php';
require_once CORE_PATH   . '/Database.php';
require_once CORE_PATH   . '/Model.php';
require_once CORE_PATH   . '/Controller.php';
require_once CORE_PATH   . '/Router.php';

session_start();

$router = new Router();

// Public
$router->get('/',                 'HomeController',   'index');
$router->get('/login',            'AuthController',   'loginForm');
$router->post('/login',           'AuthController',   'login');
$router->get('/register',         'AuthController',   'registerForm');
$router->post('/register',        'AuthController',   'register');
$router->get('/logout',           'AuthController',   'logout');

// Teams
$router->get('/teams',            'TeamController',   'index');
$router->get('/teams/create',     'TeamController',   'createForm');
$router->post('/teams/create',    'TeamController',   'create');
$router->get('/teams/{id}',       'TeamController',   'detail');

// Matches
$router->get('/matches',          'MatchController',  'index');
$router->get('/matches/{id}',     'MatchController',  'detail');

// Leagues
$router->get('/leagues',          'LeagueController', 'index');
$router->get('/leagues/{id}',     'LeagueController', 'detail');

// User
$router->get('/dashboard',        'UserController',   'dashboard');
$router->get('/profile',          'UserController',   'profile');
$router->post('/profile/update',  'UserController',   'update');

// Chat
$router->get('/chat',             'ChatController',   'index');
$router->get('/chat/{id}',        'ChatController',   'room');

// Admin
$router->get('/admin',            'AdminController',  'dashboard');
$router->get('/admin/users',      'AdminController',  'users');
$router->get('/admin/teams',      'AdminController',  'teams');
$router->get('/admin/leagues',    'AdminController',  'leagues');
$router->get('/admin/fields',     'AdminController',  'fields');

$router->dispatch();
