<?php
namespace App\Controllers;

use App\Models\Paciente;

class PacienteController
{
    private $pacienteModel;
    
    public function __construct()
    {
        $this->pacienteModel = new Paciente();
    }
    
    public function index()
    {
        $pacientes = $this->pacienteModel->getAll();
        echo json_encode($pacientes);
    }
    
    public function show($id)
    {
        $paciente = $this->pacienteModel->getById($id);
        if ($paciente) {
            echo json_encode($paciente);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Paciente não encontrado']);
        }
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['nome_usuario']) || !isset($data['email_usuario']) || !isset($data['senha_usuario']) || !isset($data['cpf'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nome, email, senha e CPF são obrigatórios']);
            return;
        }
        
        $userData = [
            'nome_usuario' => $data['nome_usuario'],
            'email_usuario' => $data['email_usuario'],
            'senha_usuario' => $data['senha_usuario'],
            'status_usuario' => 1
        ];
        
        $pacienteData = [
            'cpf' => $data['cpf'],
            'nis' => $data['nis'] ?? null
        ];
        
        $paciente = $this->pacienteModel->create($userData, $pacienteData);
        if($paciente) {
            http_response_code(201);
            echo json_encode($paciente);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar paciente']);
        }
    }
}
