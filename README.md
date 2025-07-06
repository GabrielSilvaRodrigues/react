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
   - phpMyAdmin: http://localhost:8080
   - MySQL: localhost:3306

### Acesso ao phpMyAdmin

Para gerenciar o banco de dados através do phpMyAdmin:

1. **Acessar**: http://localhost:8080
2. **Credenciais**:
   - Servidor: `mysql`
   - Usuário: `root`
   - Senha: `root123`

Ou usar o comando:
```bash
make phpmyadmin
```

### Comandos Úteis

```bash
# Ver URLs de todos os serviços
make urls

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

# Informações do phpMyAdmin
make phpmyadmin
```

### Desenvolvimento

Para desenvolvimento, você pode executar serviços individualmente:

```bash
# Apenas backend + banco + phpMyAdmin
docker-compose up mysql phpmyadmin backend

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

## Serviços Disponíveis

- **MySQL**: Banco de dados principal
- **phpMyAdmin**: Interface web para gerenciamento do MySQL
- **Backend PHP**: API REST com endpoints para todas as entidades
- **Frontend React**: Interface de usuário moderna

## Variáveis de Ambiente

- `REACT_APP_API_URL`: URL da API para o frontend
- `DB_HOST`: Host do banco de dados
- `DB_NAME`: Nome do banco de dados
- `DB_USER`: Usuário do banco
- `DB_PASS`: Senha do banco
- `PMA_HOST`: Host do MySQL para phpMyAdmin
