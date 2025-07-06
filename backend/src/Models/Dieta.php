<?php
namespace App\Models;

class Dieta
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM dieta ORDER BY data_inicio_dieta DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO dieta (data_inicio_dieta, data_termino_dieta, descricao_dieta) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $data['data_inicio_dieta']);
        $stmt->bindParam(2, $data['data_termino_dieta']);
        $stmt->bindParam(3, $data['descricao_dieta']);
        
        if($stmt->execute()) {
            $data['id_dieta'] = $this->conn->lastInsertId();
            return $data;
        }
        
        return false;
    }

    public function getDietasPaciente($id_paciente)
    {
        $query = "SELECT d.* FROM dieta d 
                  INNER JOIN relacao_paciente_dieta rpd ON d.id_dieta = rpd.id_dieta 
                  WHERE rpd.id_paciente = ?
                  ORDER BY d.data_inicio_dieta DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
