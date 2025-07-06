<?php
namespace App\Models;

class Consulta
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM consulta ORDER BY data_consulta DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO consulta (data_consulta) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $data['data_consulta']);
        
        if($stmt->execute()) {
            $data['id_consulta'] = $this->conn->lastInsertId();
            return $data;
        }
        
        return false;
    }
}
