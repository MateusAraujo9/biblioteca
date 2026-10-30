# Biblioteca - Cadastro de Livros

Teste técnico: sistema web para cadastro de Livros, Autores e Assuntos.

## Tecnologias

- PHP 8.4 + Symfony 8.1
- Twig + Bootstrap 5
- Mysql 8 + Doctrine ORM
- PHPUnit (TDD)
- Docker
- Git

## Pré requisitos

- Docker e Docker Compose
- Git

## Como executar

```bash
# 1. Clonar o repositório
git clone https://github.com/MateusAraujo9/biblioteca.git biblioteca
cd biblioteca

# 2. Subir os containers
docker compose up -d --build

# 3. Instalar as dependências
docker compose exec app composer install

# 4. Criar as tabelas no banco
docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction
```

Acesse: **http://localhost:8080**

| Serviço | Endereço | Credenciais |
|---|---|---|
| Aplicação | http://localhost:8080 | — |
| MySQL | localhost:3307 | usuário `app` / senha `app` / banco `biblioteca` |

## Como rodar os testes

```bash
# 1. Criar as tabelas no banco de teste na primeira vez que rodar os testes
docker compose exec app php bin/console doctrine:migrations:migrate --env=test --no-interaction

# 2. Rodar a suíte
docker compose exec app php bin/phpunit
```

## Status

- [x] Ambiente Docker (PHP + Apache + MySQL)
- [x] Projeto Symfony criado
- [x] Entidades e migrations
- [ ] CRUD de Autor

...