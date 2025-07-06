<?php
namespace App\Controllers;

use App\Models\User;

class UserController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function index()
    {
        $users = $this->userModel->getAll();
        echo json_encode($users);
    }
    
    public function show($id)
    {
        $user = $this->userModel->getById($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['name']) || !isset($data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nome e email são obrigatórios']);
            return;
        }
        
        $user = $this->userModel->create($data);
        http_response_code(201);
        echo json_encode($user);
    }
    
    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user = $this->userModel->update($id, $data);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
    
    public function destroy($id)
    {
        $deleted = $this->userModel->delete($id);
        if ($deleted) {
            echo json_encode(['message' => 'Usuário deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
}
