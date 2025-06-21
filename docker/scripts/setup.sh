#!/bin/sh

echo "=== Configurando ambiente Laravel 12 para Docker ==="

# Aguardar os serviços ficarem prontos
echo "Aguardando Redis ficar pronto..."
sleep 10

# Copiar .env.docker para .env se não existir
if [ ! -f /var/www/.env ]; then
    echo "Copiando .env.docker para .env..."
    cp /var/www/.env.docker /var/www/.env
fi

# Criar banco SQLite se não existir
if [ ! -f /var/www/database/database.sqlite ]; then
    echo "Criando banco de dados SQLite..."
    touch /var/www/database/database.sqlite
    chmod 664 /var/www/database/database.sqlite
fi

# Gerar chave da aplicação se não existir
if ! grep -q "APP_KEY=base64:" /var/www/.env; then
    echo "Gerando chave da aplicação..."
    php artisan key:generate --no-interaction
fi

# Executar migrações
echo "Executando migrações..."
php artisan migrate --force --no-interaction

# Limpar caches
echo "Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Testar conexão com Redis
echo "Testando conexão com Redis..."
php artisan tinker --execute="Redis::connection()->ping();"

# Testar conexão com SQLite
echo "Testando conexão com SQLite..."
php artisan tinker --execute="DB::connection()->getPdo();"

echo "=== Configuração concluída ==="
