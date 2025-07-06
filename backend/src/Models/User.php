<?php
namespace App\Models;

class User
{
    private $users = [];
    private $nextId = 1;
    
    public function __construct()
    {
        // Dados simulados
        $this->users = [
            ['id' => 1, 'name' => 'João Silva', 'email' => 'joao@email.com'],
            ['id' => 2, 'name' => 'Maria Santos', 'email' => 'maria@email.com']
        ];
        $this->nextId = 3;
    }
    
    public function getAll()
    {
        return $this->users;
    }
    
    public function getById($id)
    {
        foreach ($this->users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    
    public function create($data)
    {
        $user = [
            'id' => $this->nextId++,
            'name' => $data['name'],
            'email' => $data['email']
        ];
        $this->users[] = $user;
        return $user;
    }
    
    public function update($id, $data)
    {
        foreach ($this->users as &$user) {
            if ($user['id'] == $id) {
                $user['name'] = $data['name'] ?? $user['name'];
                $user['email'] = $data['email'] ?? $user['email'];
                return $user;
            }
        }
        return null;
    }
    
    public function delete($id)
    {
        foreach ($this->users as $index => $user) {
            if ($user['id'] == $id) {
                unset($this->users[$index]);
                $this->users = array_values($this->users);
                return true;
            }
        }
        return false;
    }
}
