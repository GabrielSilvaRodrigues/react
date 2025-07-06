.PHONY: up down build logs clean install

# Subir todos os serviços
up:
	docker-compose up -d

# Parar todos os serviços
down:
	docker-compose down

# Construir imagens
build:
	docker-compose build

# Ver logs
logs:
	docker-compose logs -f

# Limpar containers e volumes
clean:
	docker-compose down -v
	docker system prune -f

# Instalar dependências
install:
	docker-compose run --rm backend composer install
	docker-compose run --rm frontend npm install

# Executar backend
backend:
	docker-compose up backend mysql

# Executar frontend
frontend:
	docker-compose up frontend

# Acessar container do backend
backend-shell:
	docker-compose exec backend bash

# Acessar container do frontend
frontend-shell:
	docker-compose exec frontend sh

# Acessar MySQL
mysql:
	docker-compose exec mysql mysql -u delimeter_user -p delimeter

# Acessar phpMyAdmin
phpmyadmin:
	@echo "Acessando phpMyAdmin em: http://localhost:8080"
	@echo "Usuário: root"
	@echo "Senha: root123"
	@echo "Servidor: mysql"

# Mostrar URLs dos serviços
urls:
	@echo "=== URLs dos Serviços ==="
	@echo "Frontend:    http://localhost:3000"
	@echo "Backend API: http://localhost:8000"
	@echo "phpMyAdmin:  http://localhost:8080"
	@echo "=========================="

# Comandos específicos para GitHub Codespaces
codespaces-setup:
	@echo "Configurando para GitHub Codespaces..."
	docker-compose up -d mysql phpmyadmin backend
	@echo "Aguardando serviços iniciarem..."
	sleep 10
	@echo "Executando frontend..."
	docker-compose up frontend

# Verificar status dos serviços
status:
	docker-compose ps

# Ver logs específicos
logs-backend:
	docker-compose logs -f backend

logs-mysql:
	docker-compose logs -f mysql
