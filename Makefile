.PHONY: up down build restart migrate fresh shell logs

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

restart:
	docker compose restart

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh

setup: build
	docker compose up -d
	sleep 5
	docker compose exec -u root app chown -R www:www /var/www/storage /var/www/bootstrap/cache /var/www/.env
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate --force

shell:
	docker compose exec app bash

logs:
	docker compose logs -f

artisan:
	docker compose exec app php artisan $(cmd)

npm-build:
	docker run --rm -v $(PWD)/src:/app -w /app node:20-alpine npm run build

npm-install:
	docker run --rm -v $(PWD)/src:/app -w /app node:20-alpine npm install
