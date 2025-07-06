<?php
namespace App\Models;

class Paciente
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT p.id_paciente, p.cpf, p.nis, u.nome_usuario, u.email_usuario 
                  FROM paciente p 
                  INNER JOIN usuario u ON p.id_usuario = u.id_usuario 
                  WHERE u.status_usuario = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT p.id_paciente, p.cpf, p.nis, u.nome_usuario, u.email_usuario 
                  FROM paciente p 
                  INNER JOIN usuario u ON p.id_usuario = u.id_usuario 
                  WHERE p.id_paciente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userData, $pacienteData)
    {
        try {
            $this->conn->beginTransaction();
            
            // Criar usuário primeiro
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->create($userData);
            
            if(!$usuario) {
                throw new Exception("Erro ao criar usuário");
            }
            
            // Criar paciente
            $query = "INSERT INTO paciente (id_usuario, cpf, nis) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $usuario['id_usuario']);
            $stmt->bindParam(2, $pacienteData['cpf']);
            $stmt->bindParam(3, $pacienteData['nis']);
            
            if($stmt->execute()) {
                $this->conn->commit();
                return [
                    'id_paciente' => $this->conn->lastInsertId(),
                    'cpf' => $pacienteData['cpf'],
                    'nis' => $pacienteData['nis'],
                    'nome_usuario' => $usuario['nome_usuario'],
                    'email_usuario' => $usuario['email_usuario']
                ];
            }
            
            throw new Exception("Erro ao criar paciente");
            
        } catch(Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
