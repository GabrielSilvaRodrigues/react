<?php
require_once '../vendor/autoload.php';

use App\Controllers\UserController;
use App\Core\Router;

// Configurar CORS
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$router = new Router();
$userController = new UserController();

// Rotas da API
$router->get('/api/users', [$userController, 'index']);
$router->get('/api/users/{id}', [$userController, 'show']);
$router->post('/api/users', [$userController, 'store']);
$router->put('/api/users/{id}', [$userController, 'update']);
$router->delete('/api/users/{id}', [$userController, 'destroy']);

$router->handle();
