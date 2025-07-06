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
