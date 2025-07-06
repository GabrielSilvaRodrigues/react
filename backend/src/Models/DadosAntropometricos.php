<?php
namespace App\Models;

class DadosAntropometricos
{
    private $conn;
    
    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT da.*, p.cpf, u.nome_usuario 
                  FROM dados_antropometricos da 
                  INNER JOIN paciente p ON da.id_paciente = p.id_paciente 
                  INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                  ORDER BY da.data_medida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDadosPaciente($id_paciente)
    {
        $query = "SELECT * FROM dados_antropometricos WHERE id_paciente = ? ORDER BY data_medida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO dados_antropometricos (id_paciente, sexo_paciente, altura_paciente, peso_paciente, status_paciente, data_medida) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $data['id_paciente']);
        $stmt->bindParam(2, $data['sexo_paciente']);
        $stmt->bindParam(3, $data['altura_paciente']);
        $stmt->bindParam(4, $data['peso_paciente']);
        $stmt->bindParam(5, $data['status_paciente']);
        $stmt->bindParam(6, $data['data_medida']);
        
        if($stmt->execute()) {
            $data['id_medida'] = $this->conn->lastInsertId();
            return $data;
        }
        
        return false;
    }
}
