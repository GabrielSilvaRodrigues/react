<?php
namespace App\Models;

class Usuario
{
    private $conn;
    private $table_name = "usuario";

    public function __construct()
    {
        $database = new \Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT id_usuario, nome_usuario, email_usuario, status_usuario FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT id_usuario, nome_usuario, email_usuario, status_usuario FROM " . $this->table_name . " WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (nome_usuario, email_usuario, senha_usuario, status_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $senha_hash = password_hash($data['senha_usuario'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(1, $data['nome_usuario']);
        $stmt->bindParam(2, $data['email_usuario']);
        $stmt->bindParam(3, $senha_hash);
        $stmt->bindParam(4, $data['status_usuario']);
        
        if($stmt->execute()) {
            $data['id_usuario'] = $this->conn->lastInsertId();
            unset($data['senha_usuario']);
            return $data;
        }
        
        return false;
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET nome_usuario = ?, email_usuario = ?, status_usuario = ? WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $data['nome_usuario']);
        $stmt->bindParam(2, $data['email_usuario']);
        $stmt->bindParam(3, $data['status_usuario']);
        $stmt->bindParam(4, $id);
        
        if($stmt->execute()) {
            return $this->getById($id);
        }
        
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        
        return $stmt->execute();
    }
}
