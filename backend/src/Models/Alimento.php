<?php
namespace App\Models;

class Alimento
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM alimento ORDER BY descricao_alimento";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO alimento (descricao_alimento, dados_nutricionais) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $data['descricao_alimento']);
        $stmt->bindParam(2, $data['dados_nutricionais']);
        
        if($stmt->execute()) {
            $data['id_alimento'] = $this->conn->lastInsertId();
            return $data;
        }
        
        return false;
    }
}
