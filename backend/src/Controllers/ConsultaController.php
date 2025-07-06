<?php
namespace App\Controllers;

use App\Models\Consulta;

class ConsultaController
{
    private $consultaModel;
    
    public function __construct()
    {
        $this->consultaModel = new Consulta();
    }
    
    public function index()
    {
        $consultas = $this->consultaModel->getAll();
        echo json_encode($consultas);
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['data_consulta'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Data da consulta é obrigatória']);
            return;
        }
        
        $consulta = $this->consultaModel->create($data);
        if($consulta) {
            http_response_code(201);
            echo json_encode($consulta);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar consulta']);
        }
    }
}
