# Usage: make [command]
.PHONY: help build up down restart logs shell artisan composer npm test migrate seed fresh install

# Default command
.DEFAULT_GOAL := help

# Colors for output
YELLOW := \033[33m
GREEN := \033[32m
RED := \033[31m
NC := \033[0m

## Show this help message
help:
	@echo "$(GREEN)Laravel Docker Commands$(NC)"
	@echo "======================="
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(YELLOW)%-20s$(NC) %s\n", $$1, $$2}'

## Build Docker containers
build:
	@echo "$(GREEN)Building Docker containers...$(NC)"
	docker compose build --no-cache

## Start all services
up:
	@echo "$(GREEN)Starting services...$(NC)"
	docker compose up -d
	@echo "$(GREEN)Services started!$(NC)"
	@echo "Adminer: http://localhost:8080"

## Stop all services
down:
	@echo "$(RED)Stopping services...$(NC)"
	docker compose down

## Restart all services
restart: down up ## Stop and start services

## Show logs for all services
logs:
	docker compose logs -f

## Show logs for specific service (usage: make logs-app, make logs-nginx, etc.)
logs-%:
	docker compose logs -f $*

## Access Laravel container shell
shell:
	@echo "$(GREEN)Accessing Laravel container...$(NC)"
	docker compose exec app /bin/bash

## Execute Artisan commands (usage: make artisan cmd="migrate")
artisan:
	@if [ -z "$(cmd)" ]; then \
		echo "$(RED)Error: Please specify a command. Usage: make artisan cmd=\"migrate\"$(NC)"; \
		exit 1; \
	fi
	docker compose exec app php artisan $(cmd)

## Run common Artisan commands
migrate: ## Run database migrations
	docker compose exec app php artisan migrate

migrate-rollback: ## Rollback last migration
	docker compose exec app php artisan migrate:rollback

migrate-fresh: ## Drop all tables and re-run migrations
	docker compose exec app php artisan migrate:fresh

seed: ## Run database seeders
	docker compose exec app php artisan db:seed

fresh: ## Fresh migration with seeding
	docker compose exec app php artisan migrate:fresh --seed

clear-cache: ## Clear all caches
	docker compose exec app php artisan cache:clear
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear

optimize: ## Optimize the application
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

## Execute Composer commands (usage: make composer cmd="install")
composer:
	@if [ -z "$(cmd)" ]; then \
		echo "$(RED)Error: Please specify a command. Usage: make composer cmd=\"install\"$(NC)"; \
		exit 1; \
	fi
	docker compose exec app composer $(cmd)

## Install Composer dependencies
composer-install: ## Install Composer dependencies
	docker compose exec app composer install

## Update Composer dependencies
composer-update: ## Update Composer dependencies
	docker compose exec app composer update

## Execute NPM commands (usage: make npm cmd="install")
npm:
	@if [ -z "$(cmd)" ]; then \
		echo "$(RED)Error: Please specify a command. Usage: make npm cmd=\"install\"$(NC)"; \
		exit 1; \
	fi
	docker compose exec app npm $(cmd)

## Install NPM dependencies
npm-install: ## Install NPM dependencies
	docker compose exec app npm install

## Build assets for development
npm-dev: ## Build assets for development
	docker compose exec app npm run dev

## Build assets for production
npm-prod: ## Build assets for production
	docker compose exec app npm run build

## Watch for changes and rebuild assets
npm-watch: ## Watch for changes and rebuild assets
	docker compose exec app npm run dev -- --watch

## Run tests
test: ## Run PHPUnit tests
	docker compose exec app php artisan test

## Run specific test (usage: make test-file file="tests/Unit/ExampleTest.php")
test-file:
	@if [ -z "$(file)" ]; then \
		echo "$(RED)Error: Please specify a test file. Usage: make test-file file=\"tests/Unit/ExampleTest.php\"$(NC)"; \
		exit 1; \
	fi
	docker compose exec app php artisan test $(file)

## Generate application key
key-generate: ## Generate application key
	docker compose exec app php artisan key:generate

## Create storage symlink
storage-link: ## Create storage symlink
	docker compose exec app php artisan storage:link

## Install Laravel (for new projects)
install: build up composer-install key-generate migrate ## Complete Laravel installation

## Setup development environment
dev-setup: install npm-install npm-dev storage-link ## Complete development setup

## View container status
status: ## Show container status
	docker compose ps

clean: ## Clean up Docker resources
	docker compose down -v --remove-orphans
	docker system prune -f

## Backup database
backup: ## Backup MySQL database
	@echo "$(GREEN)Creating database backup...$(NC)"
	docker compose exec mysql mysqldump -u root -p$(grep DB_PASSWORD .env | cut -d '=' -f2) $(grep DB_DATABASE .env | cut -d '=' -f2) > backup_$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)Database backup completed!$(NC)"

## Monitor resource usage
monitor: ## Monitor container resource usage
	docker stats $(docker compose ps -q)
