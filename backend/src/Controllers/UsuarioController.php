<?php
namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController
{
    private $usuarioModel;
    
    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }
    
    public function index()
    {
        try {
            $usuarios = $this->usuarioModel->getAll();
            echo json_encode([
                'success' => true,
                'data' => $usuarios,
                'count' => count($usuarios)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function show($id)
    {
        $usuario = $this->usuarioModel->getById($id);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['nome_usuario']) || !isset($data['email_usuario']) || !isset($data['senha_usuario'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nome, email e senha são obrigatórios']);
            return;
        }
        
        $data['status_usuario'] = $data['status_usuario'] ?? 1;
        
        $usuario = $this->usuarioModel->create($data);
        if($usuario) {
            http_response_code(201);
            echo json_encode($usuario);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar usuário']);
        }
    }
    
    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $usuario = $this->usuarioModel->update($id, $data);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
    
    public function destroy($id)
    {
        $deleted = $this->usuarioModel->delete($id);
        if ($deleted) {
            echo json_encode(['message' => 'Usuário deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
}
