<?php
namespace App\Controllers;

use App\Models\Alimento;

class AlimentoController
{
    private $alimentoModel;
    
    public function __construct()
    {
        $this->alimentoModel = new Alimento();
    }
    
    public function index()
    {
        $alimentos = $this->alimentoModel->getAll();
        echo json_encode($alimentos);
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['descricao_alimento'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Descrição do alimento é obrigatória']);
            return;
        }
        
        $alimento = $this->alimentoModel->create($data);
        if($alimento) {
            http_response_code(201);
            echo json_encode($alimento);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar alimento']);
        }
    }
}
