# Tracken URL Shortener

API REST em Laravel para encurtamento de URLs: cadastro, edição, remoção, listagem e redirecionamento.

## Stack

- PHP 8.2 / Laravel 11
- MySQL 8 (via Docker Compose)
- PHPUnit (SQLite em memória para os testes)

## Como rodar

### 1. Instalar dependências

```bash
composer install
```

### 2. Configurar o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Subir o banco de dados

```bash
docker compose up -d
```

Isso sobe um MySQL 8 na porta `3306` com as credenciais já configuradas no `.env.example` (`DB_DATABASE=tracken`, `DB_USERNAME=tracken`, `DB_PASSWORD=tracken`).

Se preferir usar um MySQL já instalado na sua máquina em vez do Docker, edite as variáveis `DB_*` no `.env` com suas próprias credenciais — não é preciso mais nada.

### 4. Rodar as migrations

```bash
php artisan migrate
```

### 5. Subir o servidor

```bash
php artisan serve
```

A API fica disponível em `http://localhost:8000`.

## Endpoints

Base: `http://localhost:8000/api`

| Método | Rota | Descrição |
|---|---|---|
| POST | `/short-urls` | Cria uma URL encurtada |
| GET | `/short-urls` | Lista todas as URLs cadastradas |
| GET | `/short-urls/{id}` | Mostra uma URL específica |
| PUT/PATCH | `/short-urls/{id}` | Atualiza a URL original (o código curto não muda) |
| DELETE | `/short-urls/{id}` | Remove uma URL |

Fora do prefixo `/api` (é uma rota "de navegador", não retorna JSON):

| Método | Rota | Descrição |
|---|---|---|
| GET | `/{codigo}` | Redireciona (302) para a URL original |

### Exemplo — criar uma URL

```bash
curl -X POST http://localhost:8000/api/short-urls \
  -H "Content-Type: application/json" \
  -d '{"original_url":"https://www.tracken.com.br/produto/123"}'
```

```json
{
  "data": {
    "id": 1,
    "original_url": "https://www.tracken.com.br/produto/123",
    "short_url": "http://localhost:8000/aB3xY9",
    "short_code": "aB3xY9",
    "is_active": true,
    "created_at": "2026-08-19T12:00:00.000000Z",
    "updated_at": "2026-08-19T12:00:00.000000Z"
  }
}
```

### Erros

Todas as respostas de erro em `/api/*` seguem o formato:

```json
{ "message": "Recurso não encontrado." }
```

Erros de validação (`422`) incluem também o campo `errors`:

```json
{
  "message": "Informe uma URL válida (ex: https://meusite.com/produto/123).",
  "errors": { "original_url": ["Informe uma URL válida (ex: https://meusite.com/produto/123)."] }
}
```

## Testes

```bash
php artisan test
```

Roda em SQLite em memória — não depende do container MySQL estar de pé.

## Documentação interativa (Swagger/OpenAPI)

Com o servidor rodando, acesse:

```
http://localhost:8000/docs/api
```

Gerada automaticamente pelo [Scramble](https://scramble.dedoc.co) a partir das rotas, Form Requests e Resources — sem anotações manuais. O JSON puro (OpenAPI 3.1) fica em `/docs/api.json`.

## Nota de segurança

`composer audit` acusa 2 vulnerabilidades conhecidas no `laravel/framework` (versão 11.x): uma envolvendo *signed URLs* e outra a regra de validação `email`. Nenhuma das duas é explorável nesta aplicação — não usamos rotas assinadas nem a regra `email` em nenhum lugar. Não há correção disponível dentro da 11.x (só a partir da 12.60+/13.10+); migrar de major version está fora do escopo desta entrega.

