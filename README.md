<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# API de Municípios Brasileiros

API RESTful para consulta de informações sobre municípios brasileiros, desenvolvida com Laravel 12 e PHP 8.4.

## 🚀 Tecnologias

- **Laravel 12** - Framework PHP
- **PHP 8.4** - Linguagem de programação
- **Redis** - Cache e sessões
- **Docker** - Containerização
- **Nginx** - Servidor web

## 🚀 Instalação e Deploy

<details open>
<summary><strong>🐳 Docker (Recomendado)</strong></summary>

### Pré-requisitos
- Docker
- Docker Compose

### Início rápido

1. **Clone o repositório:**

```bash
git clone <url-do-repositorio>
cd br-municipios-api
```

2. **Construa e inicie os containers:**

```bash
# Instalação completa (build + up + setup)
make install
```

3. **Acesse a aplicação:**

- API: <http://localhost:8001>
- Redis: localhost:6379

### Comandos úteis

```bash
# Ver todos os comandos disponíveis
make help

# Comandos principais
make up              # Iniciar containers
make down            # Parar containers
make restart         # Reiniciar containers
make build           # Construir imagens Docker
make rebuild         # Reconstruir imagens (sem cache)
make logs            # Ver logs de todos os containers
make logs-app        # Ver logs apenas da aplicação

# Desenvolvimento
make shell           # Acessar terminal do container
make test            # Executar testes
make cache-clear     # Limpar todos os caches
make migrate         # Executar migrações
make status          # Status dos containers

# Comandos avançados
make artisan COMMAND="route:list"      # Executar comandos Artisan
make composer COMMAND="require package" # Executar comandos Composer
make clean           # Remover containers e volumes
```

### Estrutura dos containers

- **app**: Aplicação Laravel (PHP 8.4-FPM)
- **webserver**: Nginx (porta 8001)
- **redis**: Redis para cache e sessões

### Troubleshooting Docker

**Problemas de permissão:**

```bash
make shell
chown -R www:www /var/www
chmod -R 775 /var/www/storage
```

**Limpar cache:**

```bash
make cache-clear
```

**Verificar status dos serviços:**

```bash
make status
make logs
```

</details>

<details>
<summary><strong>🐧 Linux (Ubuntu/Debian)</strong></summary>

### Pré-requisitos

```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar PHP 8.4 e extensões
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.4 php8.4-cli php8.4-fpm php8.4-mysql php8.4-xml php8.4-curl php8.4-mbstring php8.4-zip php8.4-sqlite3 php8.4-redis -y

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar Redis
sudo apt install redis-server -y
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Instalar Nginx (opcional)
sudo apt install nginx -y
```

### Instalação

1. **Clone o repositório:**

```bash
git clone <url-do-repositorio>
cd br-municipios-api
```

2. **Instalar dependências:**

```bash
composer install
```

3. **Configurar ambiente:**

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Criar banco SQLite
touch database/database.sqlite
chmod 664 database/database.sqlite

# Executar migrações
php artisan migrate
```

4. **Configurar permissões:**

```bash
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

5. **Iniciar aplicação:**

```bash
# Servidor de desenvolvimento
php artisan serve --host=0.0.0.0 --port=8000

# Ou usar Nginx (configuração necessária)
```

### Configuração Redis (Linux)

Edite o arquivo `.env`:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

</details>

<details>
<summary><strong>🪟 Windows</strong></summary>

### Pré-requisitos

1. **Instalar PHP 8.4:**
   - Baixe de: <https://windows.php.net/download/>
   - Extraia para `C:\php`
   - Adicione `C:\php` ao PATH do sistema

2. **Configurar PHP:**
   - Copie `php.ini-development` para `php.ini`
   - Habilite extensões necessárias no `php.ini`:

```ini
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_sqlite
extension=sqlite3
extension=zip
extension=redis
```

3. **Instalar Composer:**
   - Baixe de: <https://getcomposer.org/download/>
   - Execute o instalador

4. **Instalar Redis:**
   - Baixe de: <https://github.com/microsoftarchive/redis/releases>
   - Ou use WSL2 com Docker

### Instalação

1. **Clone o repositório:**

```cmd
git clone <url-do-repositorio>
cd br-municipios-api
```

2. **Instalar dependências:**

```cmd
composer install
```

3. **Configurar ambiente:**

```cmd
# Copiar arquivo de configuração
copy .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Criar banco SQLite
type nul > database\database.sqlite

# Executar migrações
php artisan migrate
```

4. **Iniciar aplicação:**

```cmd
# Servidor de desenvolvimento
php artisan serve --host=0.0.0.0 --port=8000
```

### Configuração Redis (Windows)

Se usar Redis local, edite o arquivo `.env`:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Alternativa com WSL2

Para melhor experiência no Windows, recomendamos usar WSL2:

```cmd
# Instalar WSL2
wsl --install

# Usar Ubuntu e seguir instruções de Linux
```

</details>

## ⚙️ Configuração

O projeto está configurado para usar:

- **SQLite** como banco de dados (arquivo: `database/database.sqlite`)
- **Redis** para cache e sessões
- **Timezone**: America/Sao_Paulo
- **PHP 8.4** com extensões necessárias
- **Laravel 12** com otimizações de produção
