<?php
namespace App\Controllers;

use App\Models\Nutricionista;

class NutricionistaController
{
    private $nutricionistaModel;
    
    public function __construct()
    {
        $this->nutricionistaModel = new Nutricionista();
    }
    
    public function index()
    {
        $nutricionistas = $this->nutricionistaModel->getAll();
        echo json_encode($nutricionistas);
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['nome_usuario']) || !isset($data['email_usuario']) || !isset($data['senha_usuario']) || !isset($data['cpf']) || !isset($data['crm_nutricionista'])) {
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
        
        $nutricionistaData = [
            'cpf' => $data['cpf'],
            'crm_nutricionista' => $data['crm_nutricionista']
        ];
        
        $nutricionista = $this->nutricionistaModel->create($userData, $nutricionistaData);
        if($nutricionista) {
            http_response_code(201);
            echo json_encode($nutricionista);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar nutricionista']);
        }
    }
}
