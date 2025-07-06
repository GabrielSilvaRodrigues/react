# Sistema de Gestão Médica

Sistema completo com backend PHP e frontend React usando Docker.

## Pré-requisitos

- Docker
- Docker Compose

## Instruções para Execução

### Usando Docker Compose

1. **Clonar o projeto e navegar para o diretório**:
   ```bash
   cd /workspaces/react
   ```

2. **Construir e executar todos os serviços**:
   ```bash
   make up
   # ou
   docker-compose up -d
   ```

3. **Acessar a aplicação**:
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8000/api
   - MySQL: localhost:3306

### Comandos Úteis

```bash
# Ver logs dos serviços
make logs

# Parar todos os serviços
make down

# Reconstruir imagens
make build

# Limpar containers e volumes
make clean

# Acessar shell do backend
make backend-shell

# Acessar shell do frontend
make frontend-shell

# Acessar MySQL
make mysql
```

### Desenvolvimento

Para desenvolvimento, você pode executar serviços individualmente:

```bash
# Apenas backend + banco
make backend

# Apenas frontend
make frontend
```

## Estrutura do Projeto

```
/workspaces/react/
├── backend/
│   ├── src/
│   ├── public/
│   ├── config/
│   ├── Dockerfile
│   └── composer.json
├── src/
├── public/
├── docker-compose.yml
├── frontend.Dockerfile
├── delimeter.sql
└── Makefile
```

## Variáveis de Ambiente

- `REACT_APP_API_URL`: URL da API para o frontend
- `DB_HOST`: Host do banco de dados
- `DB_NAME`: Nome do banco de dados
- `DB_USER`: Usuário do banco
- `DB_PASS`: Senha do banco
