#!/bin/sh
set -e

# Cria .env se não existir
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Gera APP_KEY se ainda não estiver definida
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
  php artisan key:generate --force
fi

# Cria diretórios de storage (mapeados para ./data/storage) e cache com permissão
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs storage/app/public bootstrap/cache
chmod -R 777 storage bootstrap/cache

# Se DB_HOST estiver configurado, aguarda o PostgreSQL
if [ -n "$DB_HOST" ]; then
  echo "Aguardando PostgreSQL em $DB_HOST:${DB_PORT:-5432}..."
  until php -r "
    try {
      new PDO('pgsql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT') ?: '5432').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
      exit(0);
    } catch (Throwable \$e) {
      exit(1);
    }
  " 2>/dev/null; do
    sleep 1
  done
  echo "PostgreSQL conectado!"

  # Executa migrations automaticamente
  php artisan migrate --force

  # Executa seed caso não existam usuários
  USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
  if [ "$USER_COUNT" = "0" ]; then
    echo "Populando banco inicial (db:seed)..."
    php artisan db:seed --force || true
  fi
fi

# Se estiver em produção, otimiza caches
if [ "$APP_ENV" = "production" ]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

# Se foi passado um comando customizado (ex: php artisan test), executa ele
if [ $# -gt 0 ]; then
  exec "$@"
fi

# Padrão: inicia o servidor embutido na porta configurada
PORT="${PORT:-8000}"
echo "Iniciando aplicacao na porta $PORT..."
exec php artisan serve --host 0.0.0.0 --port "$PORT"
