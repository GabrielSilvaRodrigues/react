<?php
namespace App\Controllers;

use App\Models\DadosAntropometricos;

class DadosAntropometricosController
{
    private $dadosModel;
    
    public function __construct()
    {
        $this->dadosModel = new DadosAntropometricos();
    }
    
    public function index()
    {
        $dados = $this->dadosModel->getAll();
        echo json_encode($dados);
    }
    
    public function getDadosPaciente($id_paciente)
    {
        $dados = $this->dadosModel->getDadosPaciente($id_paciente);
        echo json_encode($dados);
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['id_paciente']) || !isset($data['altura_paciente']) || !isset($data['peso_paciente'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Paciente, altura e peso são obrigatórios']);
            return;
        }
        
        $data['data_medida'] = $data['data_medida'] ?? date('Y-m-d');
        $data['status_paciente'] = $data['status_paciente'] ?? 1;
        
        $dados = $this->dadosModel->create($data);
        if($dados) {
            http_response_code(201);
            echo json_encode($dados);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao salvar dados antropométricos']);
        }
    }
}
