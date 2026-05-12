#!/usr/bin/env bash
# =============================================================================
# deploy.sh — Pull latest branch and update the environment
#
# Usage:
#   bash scripts/deploy.sh <env>
#
# Environments:
#   local       — no --no-dev, no cache, no maintenance mode (default)
#   test        — no --no-dev, caches enabled, no maintenance mode
#   production  — --no-dev, all caches, maintenance mode on/off
#
# Examples:
#   bash scripts/deploy.sh local
#   bash scripts/deploy.sh test
#   bash scripts/deploy.sh production
# =============================================================================

set -euo pipefail

# ── Colours ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

info()    { echo -e "${CYAN}[INFO]${NC}  $*"; }
success() { echo -e "${GREEN}[OK]${NC}    $*"; }
warn()    { echo -e "${YELLOW}[WARN]${NC}  $*"; }
error()   { echo -e "${RED}[ERROR]${NC} $*"; exit 1; }
banner()  { echo -e "\n${BOLD}${CYAN}==> $*${NC}\n"; }

# ── Parse environment argument ────────────────────────────────────────────────
ENV="${1:-}"

if [[ -z "$ENV" ]]; then
    echo -e "${YELLOW}Usage:${NC} bash scripts/deploy.sh <env>"
    echo ""
    echo "  Environments:"
    echo "    local       — dev dependencies kept, no caches, no maintenance mode"
    echo "    test        — dev dependencies kept, caches enabled, no maintenance mode"
    echo "    production  — no dev dependencies, all caches, maintenance mode on/off"
    echo ""
    error "No environment specified."
fi

case "$ENV" in
    local|test|production) ;;
    *) error "Unknown environment '$ENV'. Must be: local, test, or production." ;;
esac

# ── Source .env early so production checks can read it ───────────────────────
_SCRIPT_DIR_EARLY="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
_PROJECT_DIR_EARLY="$(cd "$_SCRIPT_DIR_EARLY/../src" && pwd)"
if [[ -f "$_PROJECT_DIR_EARLY/.env" ]]; then
    set -a
    # shellcheck source=/dev/null
    source "$_PROJECT_DIR_EARLY/.env"
    set +a
fi
unset _SCRIPT_DIR_EARLY _PROJECT_DIR_EARLY

# Production safety checks: avoid accidental port/domain defaults.
if [[ "$ENV" == "production" ]]; then
    if [[ -z "${WEBSERVER_PORT:-}" ]]; then
        error "WEBSERVER_PORT is not set. Set it in production .env (e.g. WEBSERVER_PORT=80)."
    fi

    if [[ -z "${APP_URL:-}" ]]; then
        error "APP_URL is not set. Set it in production .env (e.g. APP_URL=https://your-domain.com)."
    fi
fi

# ── Environment-specific flags ────────────────────────────────────────────────
USE_MAINTENANCE_MODE=false
COMPOSER_NO_DEV=false
ENABLE_CACHES=false
BRANCH_REQUIRED="main"

case "$ENV" in
    local)
        COMPOSER_NO_DEV=false
        ENABLE_CACHES=false
        USE_MAINTENANCE_MODE=false
        BRANCH_REQUIRED=""          # any branch allowed without warning
        ;;
    test)
        COMPOSER_NO_DEV=false
        ENABLE_CACHES=true
        USE_MAINTENANCE_MODE=false
        BRANCH_REQUIRED=""
        ;;
    production)
        COMPOSER_NO_DEV=true
        ENABLE_CACHES=true
        USE_MAINTENANCE_MODE=true
        BRANCH_REQUIRED="main"
        ;;
esac

# ── Resolve project root ──────────────────────────────────────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$PROJECT_DIR"

# ── Header ────────────────────────────────────────────────────────────────────
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║         DEPLOY  —  ENV: $(printf '%-16s' "${ENV^^}")  ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════╝${NC}"
echo ""
info "Working directory : $PROJECT_DIR"
info "Environment       : $ENV"
info "Composer --no-dev : $COMPOSER_NO_DEV"
info "Caches            : $ENABLE_CACHES"
info "Maintenance mode  : $USE_MAINTENANCE_MODE"
echo ""

# ── 1. Branch check ───────────────────────────────────────────────────────────
banner "1. Checking branch"
BRANCH=$(git rev-parse --abbrev-ref HEAD)
if [[ -n "$BRANCH_REQUIRED" && "$BRANCH" != "$BRANCH_REQUIRED" ]]; then
    warn "Current branch is '$BRANCH', not '$BRANCH_REQUIRED'."
    read -rp "Continue deploying from '$BRANCH'? [y/N] " confirm
    [[ "$confirm" =~ ^[Yy]$ ]] || error "Deployment aborted."
fi
success "Branch: $BRANCH"

# ── 2. Uncommitted changes ────────────────────────────────────────────────────
banner "2. Checking for uncommitted changes"
if ! git diff --quiet || ! git diff --cached --quiet; then
    warn "You have uncommitted local changes:"
    git status --short
    read -rp "Stash them and continue? [y/N] " confirm
    [[ "$confirm" =~ ^[Yy]$ ]] || error "Deployment aborted."
    git stash push -m "deploy-stash-$(date +%Y%m%d%H%M%S)"
    success "Changes stashed."
else
    success "Working tree is clean."
fi

# ── 3. Pull latest changes ────────────────────────────────────────────────────
banner "3. Pulling latest changes"
git fetch origin "$BRANCH"
COMMITS_BEHIND=$(git rev-list HEAD..origin/"$BRANCH" --count)

if [[ "$COMMITS_BEHIND" -eq 0 ]]; then
    success "Already up to date."
else
    info "$COMMITS_BEHIND new commit(s) to apply."
    git pull origin "$BRANCH"
    success "Code updated."
fi

# ── 4. Maintenance mode on ────────────────────────────────────────────────────
if [[ "$USE_MAINTENANCE_MODE" == "true" ]]; then
    banner "4. Enabling maintenance mode"
    docker compose exec -T app php artisan down --render="errors::503" --retry=60 || true
    success "Maintenance mode ON."
else
    banner "4. Maintenance mode — skipped ($ENV)"
fi

# ── 5. Composer ───────────────────────────────────────────────────────────────
banner "5. Installing Composer dependencies"
if [[ "$COMPOSER_NO_DEV" == "true" ]]; then
    docker compose exec -T app composer install \
        --no-interaction \
        --no-dev \
        --optimize-autoloader \
        --prefer-dist
else
    docker compose exec -T app composer install \
        --no-interaction \
        --optimize-autoloader \
        --prefer-dist
fi
info "Fixing storage/bootstrap/cache permissions for www-data …"
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
success "Composer done."

# ── 6. Migrations ─────────────────────────────────────────────────────────────
banner "6. Running database migrations"
docker compose exec -T app php artisan migrate --force
success "Migrations done."

# ── 7. Caches ─────────────────────────────────────────────────────────────────
banner "7. Caches"
if [[ "$ENABLE_CACHES" == "true" ]]; then
    docker compose exec -T app php artisan config:clear
    docker compose exec -T app php artisan config:cache
    docker compose exec -T app php artisan route:clear
    docker compose exec -T app php artisan route:cache
    docker compose exec -T app php artisan view:clear
    docker compose exec -T app php artisan view:cache
    success "All caches refreshed."
else
    docker compose exec -T app php artisan config:clear
    docker compose exec -T app php artisan route:clear
    docker compose exec -T app php artisan view:clear
    success "Caches cleared (not cached — $ENV mode)."
fi

# ── 8. Restart containers ─────────────────────────────────────────────────────
banner "8. Restarting containers"
docker compose up -d --build
docker compose restart webserver
docker compose exec -T app php artisan queue:restart
success "Containers restarted."

# ── 9. Frontend assets ────────────────────────────────────────────────────────
banner "9. Frontend assets"
if [[ -f "$PROJECT_DIR/src/package.json" ]]; then
    docker compose run --rm node sh -c "npm ci --silent && npm run build"
    success "Frontend assets built."
else
    info "No package.json found — skipping."
fi

# ── 10. Maintenance mode off ──────────────────────────────────────────────────
if [[ "$USE_MAINTENANCE_MODE" == "true" ]]; then
    banner "10. Taking application back online"
    docker compose exec -T app php artisan up
    success "Application is live."
else
    banner "10. Maintenance mode — skipped ($ENV)"
fi

# ── Summary ───────────────────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}${BOLD}╔══════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}${BOLD}║  ✓  Deployment complete [$ENV] — $(date '+%Y-%m-%d %H:%M:%S')  ║${NC}"
echo -e "${GREEN}${BOLD}╚══════════════════════════════════════════════════════╝${NC}"
echo ""
git log -1 --format="  Commit: %h — %s (%ar by %an)"
echo ""
