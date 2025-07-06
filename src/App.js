import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

function App() {
  const [activeTab, setActiveTab] = useState('usuarios');
  const [usuarios, setUsuarios] = useState([]);
  const [pacientes, setPacientes] = useState([]);
  const [formData, setFormData] = useState({
    nome_usuario: '',
    email_usuario: '',
    senha_usuario: '',
    cpf: '',
    nis: ''
  });

  useEffect(() => {
    if (activeTab === 'usuarios') {
      fetchUsuarios();
    } else if (activeTab === 'pacientes') {
      fetchPacientes();
    }
  }, [activeTab]);

  const fetchUsuarios = async () => {
    try {
      const response = await axios.get(`${API_URL}/usuarios`);
      setUsuarios(response.data);
    } catch (error) {
      console.error('Erro ao buscar usuários:', error);
    }
  };

  const fetchPacientes = async () => {
    try {
      const response = await axios.get(`${API_URL}/pacientes`);
      setPacientes(response.data);
    } catch (error) {
      console.error('Erro ao buscar pacientes:', error);
    }
  };

  const handleSubmitUsuario = async (e) => {
    e.preventDefault();
    
    try {
      await axios.post(`${API_URL}/usuarios`, {
        nome_usuario: formData.nome_usuario,
        email_usuario: formData.email_usuario,
        senha_usuario: formData.senha_usuario
      });
      
      setFormData({ nome_usuario: '', email_usuario: '', senha_usuario: '', cpf: '', nis: '' });
      fetchUsuarios();
    } catch (error) {
      console.error('Erro ao salvar usuário:', error);
    }
  };

  const handleSubmitPaciente = async (e) => {
    e.preventDefault();
    
    try {
      await axios.post(`${API_URL}/pacientes`, formData);
      
      setFormData({ nome_usuario: '', email_usuario: '', senha_usuario: '', cpf: '', nis: '' });
      fetchPacientes();
    } catch (error) {
      console.error('Erro ao salvar paciente:', error);
    }
  };

  const handleDeleteUsuario = async (id) => {
    if (window.confirm('Tem certeza que deseja deletar este usuário?')) {
      try {
        await axios.delete(`${API_URL}/usuarios/${id}`);
        fetchUsuarios();
      } catch (error) {
        console.error('Erro ao deletar usuário:', error);
      }
    }
  };

  return (
    <div className="App">
      <header className="App-header">
        <h1>Sistema de Gestão Médica</h1>
        
        <div className="tabs">
          <button 
            className={activeTab === 'usuarios' ? 'active' : ''}
            onClick={() => setActiveTab('usuarios')}
          >
            Usuários
          </button>
          <button 
            className={activeTab === 'pacientes' ? 'active' : ''}
            onClick={() => setActiveTab('pacientes')}
          >
            Pacientes
          </button>
        </div>

        {activeTab === 'usuarios' && (
          <div>
            <form onSubmit={handleSubmitUsuario} className="user-form">
              <h2>Cadastrar Usuário</h2>
              <input
                type="text"
                placeholder="Nome"
                value={formData.nome_usuario}
                onChange={(e) => setFormData({...formData, nome_usuario: e.target.value})}
                required
              />
              <input
                type="email"
                placeholder="Email"
                value={formData.email_usuario}
                onChange={(e) => setFormData({...formData, email_usuario: e.target.value})}
                required
              />
              <input
                type="password"
                placeholder="Senha"
                value={formData.senha_usuario}
                onChange={(e) => setFormData({...formData, senha_usuario: e.target.value})}
                required
              />
              <button type="submit">Cadastrar Usuário</button>
            </form>

            <div className="users-list">
              <h2>Lista de Usuários</h2>
              {usuarios.map(usuario => (
                <div key={usuario.id_usuario} className="user-item">
                  <div>
                    <strong>{usuario.nome_usuario}</strong> - {usuario.email_usuario}
                    <br />
                    <small>Status: {usuario.status_usuario ? 'Ativo' : 'Inativo'}</small>
                  </div>
                  <div>
                    <button onClick={() => handleDeleteUsuario(usuario.id_usuario)}>Deletar</button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {activeTab === 'pacientes' && (
          <div>
            <form onSubmit={handleSubmitPaciente} className="user-form">
              <h2>Cadastrar Paciente</h2>
              <input
                type="text"
                placeholder="Nome"
                value={formData.nome_usuario}
                onChange={(e) => setFormData({...formData, nome_usuario: e.target.value})}
                required
              />
              <input
                type="email"
                placeholder="Email"
                value={formData.email_usuario}
                onChange={(e) => setFormData({...formData, email_usuario: e.target.value})}
                required
              />
              <input
                type="password"
                placeholder="Senha"
                value={formData.senha_usuario}
                onChange={(e) => setFormData({...formData, senha_usuario: e.target.value})}
                required
              />
              <input
                type="text"
                placeholder="CPF"
                value={formData.cpf}
                onChange={(e) => setFormData({...formData, cpf: e.target.value})}
                required
              />
              <input
                type="text"
                placeholder="NIS (opcional)"
                value={formData.nis}
                onChange={(e) => setFormData({...formData, nis: e.target.value})}
              />
              <button type="submit">Cadastrar Paciente</button>
            </form>

            <div className="users-list">
              <h2>Lista de Pacientes</h2>
              {pacientes.map(paciente => (
                <div key={paciente.id_paciente} className="user-item">
                  <div>
                    <strong>{paciente.nome_usuario}</strong> - {paciente.email_usuario}
                    <br />
                    <small>CPF: {paciente.cpf} {paciente.nis && `| NIS: ${paciente.nis}`}</small>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </header>
    </div>
  );
}

export default App;
