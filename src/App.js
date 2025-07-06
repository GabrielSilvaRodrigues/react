import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

const API_URL = 'http://localhost:8000/api';

function App() {
  const [users, setUsers] = useState([]);
  const [formData, setFormData] = useState({ name: '', email: '' });
  const [editing, setEditing] = useState(null);

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = async () => {
    try {
      const response = await axios.get(`${API_URL}/users`);
      setUsers(response.data);
    } catch (error) {
      console.error('Erro ao buscar usuários:', error);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    try {
      if (editing) {
        await axios.put(`${API_URL}/users/${editing}`, formData);
        setEditing(null);
      } else {
        await axios.post(`${API_URL}/users`, formData);
      }
      
      setFormData({ name: '', email: '' });
      fetchUsers();
    } catch (error) {
      console.error('Erro ao salvar usuário:', error);
    }
  };

  const handleEdit = (user) => {
    setFormData({ name: user.name, email: user.email });
    setEditing(user.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Tem certeza que deseja deletar este usuário?')) {
      try {
        await axios.delete(`${API_URL}/users/${id}`);
        fetchUsers();
      } catch (error) {
        console.error('Erro ao deletar usuário:', error);
      }
    }
  };

  const handleCancel = () => {
    setFormData({ name: '', email: '' });
    setEditing(null);
  };

  return (
    <div className="App">
      <header className="App-header">
        <h1>Gerenciador de Usuários</h1>
        
        <form onSubmit={handleSubmit} className="user-form">
          <div>
            <input
              type="text"
              placeholder="Nome"
              value={formData.name}
              onChange={(e) => setFormData({...formData, name: e.target.value})}
              required
            />
          </div>
          <div>
            <input
              type="email"
              placeholder="Email"
              value={formData.email}
              onChange={(e) => setFormData({...formData, email: e.target.value})}
              required
            />
          </div>
          <div>
            <button type="submit">
              {editing ? 'Atualizar' : 'Adicionar'} Usuário
            </button>
            {editing && (
              <button type="button" onClick={handleCancel}>
                Cancelar
              </button>
            )}
          </div>
        </form>

        <div className="users-list">
          <h2>Lista de Usuários</h2>
          {users.map(user => (
            <div key={user.id} className="user-item">
              <div>
                <strong>{user.name}</strong> - {user.email}
              </div>
              <div>
                <button onClick={() => handleEdit(user)}>Editar</button>
                <button onClick={() => handleDelete(user.id)}>Deletar</button>
              </div>
            </div>
          ))}
        </div>
      </header>
    </div>
  );
}

export default App;
