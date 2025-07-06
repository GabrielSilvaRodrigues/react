<?php
namespace App\Controllers;

use App\Models\Dieta;

class DietaController
{
    private $dietaModel;
    
    public function __construct()
    {
        $this->dietaModel = new Dieta();
    }
    
    public function index()
    {
        $dietas = $this->dietaModel->getAll();
        echo json_encode($dietas);
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['descricao_dieta'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Descrição da dieta é obrigatória']);
            return;
        }
        
        $dieta = $this->dietaModel->create($data);
        if($dieta) {
            http_response_code(201);
            echo json_encode($dieta);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar dieta']);
        }
    }
    
    public function getDietasPaciente($id_paciente)
    {
        $dietas = $this->dietaModel->getDietasPaciente($id_paciente);
        echo json_encode($dietas);
    }
}
