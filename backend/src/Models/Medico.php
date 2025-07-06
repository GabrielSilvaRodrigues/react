<?php
namespace App\Models;

class Medico
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT m.id_medico, m.crm_medico, m.cpf, u.nome_usuario, u.email_usuario 
                  FROM medico m 
                  INNER JOIN usuario u ON m.id_usuario = u.id_usuario 
                  WHERE u.status_usuario = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userData, $medicoData)
    {
        try {
            $this->conn->beginTransaction();
            
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->create($userData);
            
            if(!$usuario) {
                throw new Exception("Erro ao criar usuário");
            }
            
            $query = "INSERT INTO medico (id_usuario, crm_medico, cpf) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $usuario['id_usuario']);
            $stmt->bindParam(2, $medicoData['crm_medico']);
            $stmt->bindParam(3, $medicoData['cpf']);
            
            if($stmt->execute()) {
                $this->conn->commit();
                return [
                    'id_medico' => $this->conn->lastInsertId(),
                    'crm_medico' => $medicoData['crm_medico'],
                    'cpf' => $medicoData['cpf'],
                    'nome_usuario' => $usuario['nome_usuario'],
                    'email_usuario' => $usuario['email_usuario']
                ];
            }
            
            throw new Exception("Erro ao criar médico");
            
        } catch(Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
