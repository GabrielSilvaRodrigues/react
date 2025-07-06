<?php
namespace App\Controllers;

use App\Models\Medico;

class MedicoController
{
    private $medicoModel;
    
    public function __construct()
    {
        $this->medicoModel = new Medico();
    }
    
    public function index()
    {
        $medicos = $this->medicoModel->getAll();
        echo json_encode($medicos);
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['nome_usuario']) || !isset($data['email_usuario']) || !isset($data['senha_usuario']) || !isset($data['cpf']) || !isset($data['crm_medico'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nome, email, senha, CPF e CRM são obrigatórios']);
            return;
        }
        
        $userData = [
            'nome_usuario' => $data['nome_usuario'],
            'email_usuario' => $data['email_usuario'],
            'senha_usuario' => $data['senha_usuario'],
            'status_usuario' => 1
        ];
        
        $medicoData = [
            'cpf' => $data['cpf'],
            'crm_medico' => $data['crm_medico']
        ];
        
        $medico = $this->medicoModel->create($userData, $medicoData);
        if($medico) {
            http_response_code(201);
            echo json_encode($medico);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar médico']);
        }
    }
}
