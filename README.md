# Microservices Test Project

A high-performance microservices application built with PHP 8.3 and Symfony 7.3, demonstrating communication between two services using HTTP/REST.

## Architecture

This is a **monorepo** containing two microservices:

- **Provider** (port 8080) - Manages account balance and history
- **Client** (port 8090) - Consumes Provider API to add/remove credits

### Tech Stack

- PHP 8.3 with FPM
- Symfony 7.3
- MySQL 8.4
- Nginx
- Docker & Docker Compose
- Guzzle HTTP Client
- Doctrine ORM

## Prerequisites

- Docker
- Docker Compose
- Git

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url> accounting-microservices
cd accounting-microservices
```

### 2. Configure Environment Variables

> **Note:** These are test credentials for local development only. For production environments, use AWS Secrets Manager, AWS Systems Manager Parameter Store, or similar secure secret management services to store and retrieve sensitive variables during the build/deployment process.

#### Provider Service

Create `Provider/app/.env.local`:

```bash
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://admin:adminProviderPass@db:3306/provider?serverVersion=8.4.0&charset=utf8mb4"
###< doctrine/doctrine-bundle ###
```

#### Client Service

Create `Client/app/.env.local`:

```bash
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://admin:adminClientPass@db:3306/client?serverVersion=8.4.0&charset=utf8mb4"
###< doctrine/doctrine-bundle ###

###> provider-client ###
PROVIDER_BASE_URL="http://host.docker.internal:8080"
###< provider-client ###
```

### 3. Build and Start Docker Containers

#### Provider Service

```bash
docker-compose -f Provider/docker-compose.yml up -d --build
```

#### Client Service

```bash
docker-compose -f Client/docker-compose.yml up -d --build
```

### 4. Install Dependencies

#### Provider

```bash
docker exec provider-test-php composer install
```

#### Client

```bash
docker exec client-test-php composer install
```

### 5. Run Database Migrations

#### Provider

```bash
docker exec provider-test-php bin/console doctrine:migrations:migrate --no-interaction
```

#### Client

```bash
docker exec client-test-php bin/console doctrine:migrations:migrate --no-interaction
```

### 6. Seed Initial Data (Provider only)

```bash
docker exec provider-test-php bin/console doctrine:fixtures:load --no-interaction
```

This creates one account with balance = 0.00

## Development

### Clear Cache

```bash
# Provider
docker exec provider-test-php bin/console cache:clear

# Client
docker exec client-test-php bin/console cache:clear
```

### View Logs

```bash
# Provider
docker logs provider-test-php -f

# Client
docker logs client-test-php -f
```

### Run Tests

```bash
# Provider
docker exec provider-test-php bin/phpunit

# Client
docker exec client-test-php bin/phpunit
```

### Code Quality

#### PHPStan (Static Analysis)

```bash
# Provider
docker exec provider-test-php vendor/bin/phpstan analyse

# Client
docker exec client-test-php vendor/bin/phpstan analyse
```

#### PHP CS Fixer (Code Style)

```bash
# Provider - Check
docker exec provider-test-php vendor/bin/php-cs-fixer fix --dry-run --diff

# Provider - Fix
docker exec provider-test-php vendor/bin/php-cs-fixer fix

# Client - Check
docker exec client-test-php vendor/bin/php-cs-fixer fix --dry-run --diff

# Client - Fix
docker exec client-test-php vendor/bin/php-cs-fixer fix
```

## Stopping Services

```bash
# Stop Provider
docker-compose -f Provider/docker-compose.yml down

# Stop Client
docker-compose -f Client/docker-compose.yml down

# Stop all and remove volumes
docker-compose -f Provider/docker-compose.yml down -v
docker-compose -f Client/docker-compose.yml down -v
```

## License

Proprietary
