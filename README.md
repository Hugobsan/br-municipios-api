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

## 📖 Documentação da API

### Endpoint Principal

#### Listar Municípios por UF

```http
GET /api/municipios/{uf}
```

**Parâmetros:**
- `uf` (string, obrigatório): Código da Unidade Federativa (2 letras)
  - Aceita tanto maiúsculo quanto minúsculo
  - Exemplos válidos: `SP`, `sp`, `RJ`, `rj`

**Resposta de Sucesso (200):**

```json
{
  "data": [
    {
      "name": "São Paulo",
      "ibge_code": "3550308"
    },
    {
      "name": "Campinas", 
      "ibge_code": "3509502"
    },
    {
      "name": "Santos",
      "ibge_code": "3548500"
    }
  ]
}
```

**Resposta de Erro (500):**

```json
{
  "error": "Não foi possível obter a lista de municípios",
  "message": "Detalhes específicos do erro"
}
```

**Resposta com Lista Vazia (200):**

```json
{
  "data": []
}
```
### Características da API

- **Cache Inteligente**: Dados são armazenados em cache Redis por 1 hora
- **Fallback Robusto**: Utiliza múltiplos provedores (IBGE e BrasilAPI) 
- **Retry Logic**: Tentativas automáticas em caso de falha temporária
- **Formato Padronizado**: Resposta sempre no mesmo formato independente do provedor
- **Case Insensitive**: Aceita UF em maiúsculo ou minúsculo
- **Validação**: Validação automática do formato da UF (2 letras)

### Performance

- **Cache Hit**: <1ms (dados do Redis)
- **Cache Miss**: ~200-500ms (primeira consulta + cache)
- **Fallback**: ~1-2s (em caso de falha do provedor principal)

### Limitações

- Não há rate limiting implementado
- Apenas consulta por UF (não por município específico)
- Dependente de APIs externas (IBGE e BrasilAPI)

## 🧪 Testes

### Executar Testes

```bash
# Com Docker (recomendado)
make test

# Diretamente com PHPUnit
./vendor/bin/phpunit
```
### Cobertura de Testes

O projeto possui **12 testes** cobrindo os pontos críticos:

#### Testes Unitários (7 testes)

- **MunicipalityService**: Cache, fallback, retry, tratamento de erros
- **MunicipalityProviders**: Formatação de dados, tratamento de falhas HTTP

#### Testes de Feature (5 testes)

- **MunicipalityController**: Validação, tratamento de erros, respostas HTTP
- **MunicipalityServiceIntegration**: Fluxo completo end-to-end com fallback

### Estrutura de Testes

```bash
tests/
├── Feature/
│   ├── MunicipalityControllerTest.php    # Testes da API
│   └── MunicipalityServiceIntegrationTest.php  # Testes de integração
└── Unit/
    ├── MunicipalityServiceTest.php       # Testes da lógica de negócio
    └── MunicipalityProvidersTest.php     # Testes dos provedores externos
```

### Cenários Testados

- ✅ Cache hit/miss
- ✅ Fallback entre provedores
- ✅ Retry automático
- ✅ Tratamento de erros HTTP
- ✅ Validação de parâmetros
- ✅ Formatação de respostas
- ✅ Integração end-to-end

## 🏗️ Arquitetura

### Visão Geral

A API segue uma arquitetura em camadas com princípios SOLID e padrões de design bem definidos:

```bash
┌─────────────────┐
│   Controller    │  ← Validação e tratamento HTTP
└─────────────────┘
         │
┌─────────────────┐
│     Facade      │  ← Interface simplificada
└─────────────────┘
         │
┌─────────────────┐
│    Service      │  ← Lógica de negócio, cache, retry
└─────────────────┘
         │
┌─────────────────┐
│   Providers     │  ← Integração com APIs externas
└─────────────────┘
         │
┌─────────────────┐
│   APIs (IBGE,   │  ← Fontes de dados
│   BrasilAPI)    │
└─────────────────┘
```

### Componentes Principais

#### 1. Controller Layer

- **MunicipalityController**: Entrada da API, validação de parâmetros
- Tratamento de exceções e formatação de respostas HTTP

#### 2. Service Layer

- **MunicipalityService**: Lógica de negócio principal
- Gerenciamento de cache (Redis)
- Implementação de retry e fallback
- Coordenação entre providers

#### 3. Provider Layer

- **IbgeMunicipalityProvider**: Integração com API do IBGE
- **BrasilApiMunicipalityProvider**: Integração com BrasilAPI
- Interface comum: **MunicipalityProviderInterface**

#### 4. Supporting Components

- **MunicipalityProviderEnum**: Gestão e instanciação de providers
- **MunicipalityResource**: Formatação de dados de saída
- **Municipality Facade**: Interface simplificada para o service

### Fluxo de Dados

1. **Request** chega no **Controller**
2. **Controller** valida parâmetros e chama **Facade**
3. **Facade** delega para singleton do **Service** associado
4. **Service** verifica **Cache** (Redis)
5. Se cache miss, **Service** tenta **Provider** principal
6. Em caso de falha, **Service** implementa **retry** (3x por padrão)
7. Se continuar falhando, **Service** usa **fallback** (outros providers)
8. **Provider** faz requisição HTTP e formata dados
9. **Service** armazena resultado no **Cache**
10. **Response** é formatada pelo **Resource** e retornada

### Padrões Implementados

- **Repository Pattern**: Providers como repositórios de dados externos
- **Facade Pattern**: Interface simplificada para o service
- **Strategy Pattern**: Enum para seleção dinâmica de providers
- **Circuit Breaker**: Retry com fallback automático
- **Dependency Injection**: IoC container do Laravel
- **Resource Pattern**: Formatação consistente de respostas

### Configurações

#### Cache (Redis)

- **TTL**: 2592000 segundos (30 dias)
   A lista de cidades é um dado com frequência de variação extremamente baixa, o que justificaria um TTL até maior, garantindo ainda assim a confiabilidade. Contudo, para evitar que os dados de estados poucos requisitados continuem na memória consumindo recursos, o TTL foi definido para 30 dias. Vale ressaltar que mesmo se todos os municípios fossem armazenados em cache, resultaria em um consumo de menos de 1mb de memória, por esse motivo, o TTL pode ser configurável via .env para diferentes trade-offs.

- **Key Pattern**: `municipios_{uf}`
- **Driver**: Redis (configurável via `.env`)

#### Providers

- **Primary**: BrasilAPI
- **Fallback**: IBGE
- **Extensível**: Fácil adição de novos providers
   Basta criar o novo service do provider de municipios e registrá-lo no enum `MunicipalityProviderEnum`.

### Vantagens da Arquitetura

- **Escalabilidade**: Fácil adição de novos providers
- **Confiabilidade**: Múltiplos pontos de falha cobertos
- **Performance**: Cache inteligente reduz latência
- **Manutenibilidade**: Separação clara de responsabilidades
- **Testabilidade**: Cada camada pode ser testada isoladamente
