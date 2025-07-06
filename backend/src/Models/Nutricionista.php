<?php
namespace App\Models;

class Nutricionista
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT n.id_nutricionista, n.crm_nutricionista, n.cpf, u.nome_usuario, u.email_usuario 
                  FROM nutricionista n 
                  INNER JOIN usuario u ON n.id_usuario = u.id_usuario 
                  WHERE u.status_usuario = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userData, $nutricionistaData)
    {
        try {
            $this->conn->beginTransaction();
            
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->create($userData);
            
            if(!$usuario) {
                throw new Exception("Erro ao criar usuário");
            }
            
            $query = "INSERT INTO nutricionista (id_usuario, crm_nutricionista, cpf) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $usuario['id_usuario']);
            $stmt->bindParam(2, $nutricionistaData['crm_nutricionista']);
            $stmt->bindParam(3, $nutricionistaData['cpf']);
            
            if($stmt->execute()) {
                $this->conn->commit();
                return [
                    'id_nutricionista' => $this->conn->lastInsertId(),
                    'crm_nutricionista' => $nutricionistaData['crm_nutricionista'],
                    'cpf' => $nutricionistaData['cpf'],
                    'nome_usuario' => $usuario['nome_usuario'],
                    'email_usuario' => $usuario['email_usuario']
                ];
            }
            
            throw new Exception("Erro ao criar nutricionista");
            
        } catch(Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
