# Simple Learning

A Laravel + Livewire educational app with interactive math games. Fully Dockerized, mobile-friendly, with PostgreSQL.

## Tech Stack

- **Laravel 11** (PHP 8.4)
- **Livewire 4** (reactive components, no page reloads)
- **Alpine.js** (dropdown menus)
- **Tailwind CSS** (responsive UI)
- **PostgreSQL 16** (database)
- **Nginx** (web server)
- **Docker / Docker Compose** (containerized)

## Games

1. **Addition & Subtraction** — configurable operation (+/−/mix), number size (1–4 digits), question count, timer, negative results toggle, type or multiple-choice answers
2. **4th Class Multiplication** — random integers 0–12 × 0–12, configurable question count, timer, type or multiple-choice answers

## Features

- Responsive design (mobile, tablet, desktop)
- Guest play (no account needed)
- Logged-in users get progress tracking with detailed results
- Timer countdown with auto-advance when time runs out
- Instant feedback on each answer
- Full session summary with review of all questions

## Getting Started

### Prerequisites

- Docker & Docker Compose installed on Ubuntu

### First-Time Setup

```bash
# Clone / navigate to project folder
cd /path/to/math_app

# Build and start everything
make setup
```

The app will be available at **http://localhost**

### Daily Use

```bash
make up      # Start containers
make down    # Stop containers
make logs    # View logs
make shell   # Open bash inside app container
make migrate # Run new migrations
```

### Manual Setup (without Make)

```bash
# Build images
docker compose build

# Start containers
docker compose up -d

# Fix permissions & generate key
docker compose exec -u root app chown -R www:www /var/www/storage /var/www/bootstrap/cache /var/www/.env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
```

## Project Structure

```
math_app/
├── docker/
│   ├── nginx/default.conf       # Nginx configuration
│   └── php/
│       ├── Dockerfile            # PHP 8.4-FPM image
│       └── local.ini             # PHP settings
├── src/                          # Laravel application
│   ├── app/
│   │   ├── Http/Controllers/     # Progress controller
│   │   ├── Livewire/             # Game components (PHP logic)
│   │   └── Models/               # GameSession, GameAnswer
│   ├── database/migrations/      # DB schema
│   └── resources/views/          # Blade templates + Livewire views
├── docker-compose.yml
├── Makefile
└── README.md
```

## Environment

The `.env` file is pre-configured for Docker:

| Variable | Value |
|----------|-------|
| DB_CONNECTION | pgsql |
| DB_HOST | db |
| DB_PORT | 5432 |
| DB_DATABASE | simple_learning |
| DB_USERNAME | simple_user |
| DB_PASSWORD | simple_password |
