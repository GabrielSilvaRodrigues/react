<?php
// Habilitar exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Configurar CORS para GitHub Codespaces
$allowedOrigins = [
    'http://localhost:3000',
    'https://special-fortnight-wrvw9vq9974xh5jp6-3000.app.github.dev',
    'https://*.app.github.dev'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowOrigin = 'http://localhost:3000'; // default

foreach ($allowedOrigins as $allowed) {
    if (strpos($allowed, '*') !== false) {
        $pattern = str_replace('*', '.*', $allowed);
        if (preg_match('#^' . $pattern . '$#', $origin)) {
            $allowOrigin = $origin;
            break;
        }
    } elseif ($origin === $allowed) {
        $allowOrigin = $origin;
        break;
    }
}

header('Access-Control-Allow-Origin: ' . $allowOrigin);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
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

    // Rota de status da API
    $router->get('/', function() {
        echo json_encode([
            'message' => 'API Sistema de Gestão Médica',
            'version' => '1.0.0',
            'status' => 'online',
            'endpoints' => [
                'GET /' => 'Status da API',
                'GET /api/usuarios' => 'Listar usuários',
                'POST /api/usuarios' => 'Criar usuário',
                'GET /api/pacientes' => 'Listar pacientes',
                'POST /api/pacientes' => 'Criar paciente',
                'GET /api/medicos' => 'Listar médicos',
                'POST /api/medicos' => 'Criar médico',
                'GET /api/nutricionistas' => 'Listar nutricionistas',
                'POST /api/nutricionistas' => 'Criar nutricionista',
                'GET /api/dados-antropometricos' => 'Listar dados antropométricos',
                'POST /api/dados-antropometricos' => 'Criar dados antropométricos'
            ]
        ]);
    });

    $router->get('/api', function() {
        echo json_encode([
            'message' => 'API Sistema de Gestão Médica',
            'version' => '1.0.0',
            'status' => 'online'
        ]);
    });

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
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro interno do servidor',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
