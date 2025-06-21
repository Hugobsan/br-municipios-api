.PHONY: help build rebuild up down restart logs shell setup clean test

# Cores para output
GREEN := \033[32m
YELLOW := \033[33m
RED := \033[31m
NC := \033[0m # No Color

help: ## Mostra esta ajuda
	@echo "$(GREEN)Comandos disponíveis:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

build: ## Constrói as imagens Docker
	@echo "$(GREEN)🔨 Fazendo build da imagem br-municipios-api...$(NC)"
	docker build --build-arg user=www --build-arg uid=1000 -t br-municipios-api:latest .

rebuild: ## Reconstrói as imagens Docker (sem cache)
	@echo "$(GREEN)🔨 Reconstruindo imagem br-municipios-api (sem cache)...$(NC)"
	docker build --no-cache --build-arg user=www --build-arg uid=1000 -t br-municipios-api:latest .

up: ## Inicia os containers
	@echo "$(GREEN)Iniciando containers...$(NC)"
	docker-compose up -d

down: ## Para os containers
	@echo "$(YELLOW)Parando containers...$(NC)"
	docker-compose down

restart: down up ## Reinicia os containers

logs: ## Mostra os logs dos containers
	docker-compose logs -f

logs-app: ## Mostra apenas os logs da aplicação
	docker-compose logs -f app

shell: ## Acessa o shell do container da aplicação
	docker-compose exec app sh

setup: ## Executa o setup inicial do Laravel
	@echo "$(GREEN)Executando setup do Laravel...$(NC)"
	docker-compose exec app sh /var/www/docker/scripts/setup.sh

clean: ## Remove containers, volumes e imagens
	@echo "$(RED)Removendo containers, volumes e imagens...$(NC)"
	docker-compose down -v --rmi all

install: build up setup ## Instalação completa (build + up + setup)
	@echo "$(GREEN)✅ Instalação completa finalizada!$(NC)"
	@echo "$(GREEN)🌐 Aplicação disponível em: http://localhost:8001$(NC)"

test: ## Executa os testes
	docker-compose exec app php artisan test

migrate: ## Executa as migrações
	docker-compose exec app php artisan migrate

migrate-fresh: ## Recria o banco de dados e executa as migrações
	docker-compose exec app php artisan migrate:fresh

artisan: ## Executa comandos artisan (uso: make artisan COMMAND="route:list")
	docker-compose exec app php artisan $(COMMAND)

composer: ## Executa comandos composer (uso: make composer COMMAND="install")
	docker-compose exec app composer $(COMMAND)

cache-clear: ## Limpa todos os caches
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

status: ## Mostra o status dos containers
	docker-compose ps
