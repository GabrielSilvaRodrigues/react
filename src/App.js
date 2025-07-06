import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './App.css';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

function App() {
  const [activeTab, setActiveTab] = useState('usuarios');
  const [usuarios, setUsuarios] = useState([]);
  const [pacientes, setPacientes] = useState([]);
  const [medicos, setMedicos] = useState([]);
  const [nutricionistas, setNutricionistas] = useState([]);
  const [dadosAntropometricos, setDadosAntropometricos] = useState([]);
  const [formData, setFormData] = useState({
    nome_usuario: '',
    email_usuario: '',
    senha_usuario: '',
    cpf: '',
    nis: '',
    crm_medico: '',
    crm_nutricionista: '',
    id_paciente: '',
    altura_paciente: '',
    peso_paciente: '',
    sexo_paciente: '1'
  });

  useEffect(() => {
    switch(activeTab) {
      case 'usuarios':
        fetchUsuarios();
        break;
      case 'pacientes':
        fetchPacientes();
        break;
      case 'medicos':
        fetchMedicos();
        break;
      case 'nutricionistas':
        fetchNutricionistas();
        break;
      case 'dados-antropometricos':
        fetchDadosAntropometricos();
        fetchPacientes(); // Para popular o select
        break;
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

  const fetchMedicos = async () => {
    try {
      const response = await axios.get(`${API_URL}/medicos`);
      setMedicos(response.data);
    } catch (error) {
      console.error('Erro ao buscar médicos:', error);
    }
  };

  const fetchNutricionistas = async () => {
    try {
      const response = await axios.get(`${API_URL}/nutricionistas`);
      setNutricionistas(response.data);
    } catch (error) {
      console.error('Erro ao buscar nutricionistas:', error);
    }
  };

  const fetchDadosAntropometricos = async () => {
    try {
      const response = await axios.get(`${API_URL}/dados-antropometricos`);
      setDadosAntropometricos(response.data);
    } catch (error) {
      console.error('Erro ao buscar dados antropométricos:', error);
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

  const handleSubmitMedico = async (e) => {
    e.preventDefault();
    
    try {
      await axios.post(`${API_URL}/medicos`, {
        nome_usuario: formData.nome_usuario,
        email_usuario: formData.email_usuario,
        senha_usuario: formData.senha_usuario,
        cpf: formData.cpf,
        crm_medico: formData.crm_medico
      });
      
      resetForm();
      fetchMedicos();
    } catch (error) {
      console.error('Erro ao salvar médico:', error);
    }
  };

  const handleSubmitNutricionista = async (e) => {
    e.preventDefault();
    
    try {
      await axios.post(`${API_URL}/nutricionistas`, {
        nome_usuario: formData.nome_usuario,
        email_usuario: formData.email_usuario,
        senha_usuario: formData.senha_usuario,
        cpf: formData.cpf,
        crm_nutricionista: formData.crm_nutricionista
      });
      
      resetForm();
      fetchNutricionistas();
    } catch (error) {
      console.error('Erro ao salvar nutricionista:', error);
    }
  };

  const handleSubmitDados = async (e) => {
    e.preventDefault();
    
    try {
      await axios.post(`${API_URL}/dados-antropometricos`, {
        id_paciente: formData.id_paciente,
        altura_paciente: parseFloat(formData.altura_paciente),
        peso_paciente: parseFloat(formData.peso_paciente),
        sexo_paciente: parseInt(formData.sexo_paciente)
      });
      
      resetForm();
      fetchDadosAntropometricos();
    } catch (error) {
      console.error('Erro ao salvar dados antropométricos:', error);
    }
  };

  const resetForm = () => {
    setFormData({
      nome_usuario: '',
      email_usuario: '',
      senha_usuario: '',
      cpf: '',
      nis: '',
      crm_medico: '',
      crm_nutricionista: '',
      id_paciente: '',
      altura_paciente: '',
      peso_paciente: '',
      sexo_paciente: '1'
    });
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
          <button 
            className={activeTab === 'medicos' ? 'active' : ''}
            onClick={() => setActiveTab('medicos')}
          >
            Médicos
          </button>
          <button 
            className={activeTab === 'nutricionistas' ? 'active' : ''}
            onClick={() => setActiveTab('nutricionistas')}
          >
            Nutricionistas
          </button>
          <button 
            className={activeTab === 'dados-antropometricos' ? 'active' : ''}
            onClick={() => setActiveTab('dados-antropometricos')}
          >
            Dados Antropométricos
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

        {activeTab === 'medicos' && (
          <div>
            <form onSubmit={handleSubmitMedico} className="user-form">
              <h2>Cadastrar Médico</h2>
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
                placeholder="CRM"
                value={formData.crm_medico}
                onChange={(e) => setFormData({...formData, crm_medico: e.target.value})}
                required
              />
              <button type="submit">Cadastrar Médico</button>
            </form>

            <div className="users-list">
              <h2>Lista de Médicos</h2>
              {medicos.map(medico => (
                <div key={medico.id_medico} className="user-item">
                  <div>
                    <strong>{medico.nome_usuario}</strong> - {medico.email_usuario}
                    <br />
                    <small>CRM: {medico.crm_medico} | CPF: {medico.cpf}</small>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {activeTab === 'nutricionistas' && (
          <div>
            <form onSubmit={handleSubmitNutricionista} className="user-form">
              <h2>Cadastrar Nutricionista</h2>
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
                placeholder="CRM Nutricionista"
                value={formData.crm_nutricionista}
                onChange={(e) => setFormData({...formData, crm_nutricionista: e.target.value})}
                required
              />
              <button type="submit">Cadastrar Nutricionista</button>
            </form>

            <div className="users-list">
              <h2>Lista de Nutricionistas</h2>
              {nutricionistas.map(nutricionista => (
                <div key={nutricionista.id_nutricionista} className="user-item">
                  <div>
                    <strong>{nutricionista.nome_usuario}</strong> - {nutricionista.email_usuario}
                    <br />
                    <small>CRM: {nutricionista.crm_nutricionista} | CPF: {nutricionista.cpf}</small>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {activeTab === 'dados-antropometricos' && (
          <div>
            <form onSubmit={handleSubmitDados} className="user-form">
              <h2>Registrar Dados Antropométricos</h2>
              <select
                value={formData.id_paciente}
                onChange={(e) => setFormData({...formData, id_paciente: e.target.value})}
                required
              >
                <option value="">Selecione um paciente</option>
                {pacientes.map(paciente => (
                  <option key={paciente.id_paciente} value={paciente.id_paciente}>
                    {paciente.nome_usuario} - {paciente.cpf}
                  </option>
                ))}
              </select>
              <input
                type="number"
                step="0.01"
                placeholder="Altura (m)"
                value={formData.altura_paciente}
                onChange={(e) => setFormData({...formData, altura_paciente: e.target.value})}
                required
              />
              <input
                type="number"
                step="0.01"
                placeholder="Peso (kg)"
                value={formData.peso_paciente}
                onChange={(e) => setFormData({...formData, peso_paciente: e.target.value})}
                required
              />
              <select
                value={formData.sexo_paciente}
                onChange={(e) => setFormData({...formData, sexo_paciente: e.target.value})}
              >
                <option value="1">Masculino</option>
                <option value="0">Feminino</option>
              </select>
              <button type="submit">Registrar Dados</button>
            </form>

            <div className="users-list">
              <h2>Dados Antropométricos</h2>
              {dadosAntropometricos.map(dado => (
                <div key={dado.id_medida} className="user-item">
                  <div>
                    <strong>{dado.nome_usuario}</strong>
                    <br />
                    <small>
                      Altura: {dado.altura_paciente}m | Peso: {dado.peso_paciente}kg | 
                      Data: {dado.data_medida}
                    </small>
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
