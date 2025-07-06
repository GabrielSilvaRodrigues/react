<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';

use App\Controllers\UsuarioController;
use App\Controllers\PacienteController;
use App\Controllers\MedicoController;
use App\Controllers\NutricionistaController;
use App\Controllers\DietaController;
use App\Controllers\AlimentoController;
use App\Controllers\ConsultaController;
use App\Controllers\DadosAntropometricosController;
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

// Controllers
$usuarioController = new UsuarioController();
$pacienteController = new PacienteController();
$medicoController = new MedicoController();
$nutricionistaController = new NutricionistaController();
$dietaController = new DietaController();
$alimentoController = new AlimentoController();
$consultaController = new ConsultaController();
$dadosController = new DadosAntropometricosController();

// Rotas de Usuários
$router->get('/api/usuarios', [$usuarioController, 'index']);
$router->get('/api/usuarios/{id}', [$usuarioController, 'show']);
$router->post('/api/usuarios', [$usuarioController, 'store']);
$router->put('/api/usuarios/{id}', [$usuarioController, 'update']);
$router->delete('/api/usuarios/{id}', [$usuarioController, 'destroy']);

// Rotas de Pacientes
$router->get('/api/pacientes', [$pacienteController, 'index']);
$router->get('/api/pacientes/{id}', [$pacienteController, 'show']);
$router->post('/api/pacientes', [$pacienteController, 'store']);
$router->put('/api/pacientes/{id}', [$pacienteController, 'update']);

// Rotas de Médicos
$router->get('/api/medicos', [$medicoController, 'index']);
$router->post('/api/medicos', [$medicoController, 'store']);

// Rotas de Nutricionistas
$router->get('/api/nutricionistas', [$nutricionistaController, 'index']);
$router->post('/api/nutricionistas', [$nutricionistaController, 'store']);

// Rotas de Dietas
$router->get('/api/dietas', [$dietaController, 'index']);
$router->post('/api/dietas', [$dietaController, 'store']);
$router->get('/api/pacientes/{id}/dietas', [$dietaController, 'getDietasPaciente']);

// Rotas de Alimentos
$router->get('/api/alimentos', [$alimentoController, 'index']);
$router->post('/api/alimentos', [$alimentoController, 'store']);

// Rotas de Consultas
$router->get('/api/consultas', [$consultaController, 'index']);
$router->post('/api/consultas', [$consultaController, 'store']);

// Rotas de Dados Antropométricos
$router->get('/api/dados-antropometricos', [$dadosController, 'index']);
$router->post('/api/dados-antropometricos', [$dadosController, 'store']);
$router->get('/api/pacientes/{id}/dados-antropometricos', [$dadosController, 'getDadosPaciente']);

$router->handle();
