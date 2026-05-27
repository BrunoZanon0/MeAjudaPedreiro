# Redis Session Implementation - Guia de Uso

## ✅ Configurações Realizadas

### 1. Variáveis de Ambiente (.env)
```
SESSION_DRIVER=redis          # Sessões agora usam Redis
CACHE_STORE=redis             # Cache agora usa Redis
REDIS_CLIENT=predis           # Cliente Predis instalado
REDIS_HOST=redis              # Apontando para container Docker
REDIS_PORT=6379               # Porta padrão
```

### 2. Novo Serviço: UserSessionService

Localização: `app/Services/UserSessionService.php`

**Métodos Disponíveis:**

```php
// Cachear todos os dados do usuário no Redis (TTL: 24h)
UserSessionService::cacheUserData($user);

// Obter dados do usuário do Redis
$userData = UserSessionService::getUserData($userId);

// Verificar se tem CPF ou CNPJ (com fallback para DB)
$hasCpf = UserSessionService::hasCpfOrCnpj($userId);

// Obter profissão via Redis
$profession = UserSessionService::getProfession($userId);

// Atualizar campo específico no cache
UserSessionService::updateUserCache($userId, 'profession', 'pedreiro');

// Invalidar cache do usuário
UserSessionService::invalidateUserCache($userId);
```

## 🔄 Fluxo de Login com Redis

1. Usuário faz login com email/password
2. `AuthController::authenticate()` faz login
3. Imediatamente após login: `UserSessionService::cacheUserData($user)` armazena os dados no Redis
4. Validações posteriores usam Redis em vez do banco de dados
5. Session do Laravel também é armazenada no Redis

## 📊 Dados Cacheados no Redis

Chave: `user:session:{user_id}`

Valor (JSON):
```json
{
  "id": 1,
  "name": "João",
  "email": "joao@example.com",
  "profession": "pedreiro",
  "cpf": "12345678900",
  "cnpj": null,
  "google_id": null,
  "avatar": "https://..."
}
```

## 🧪 Testar Conexão com Redis

Execute o comando artisan customizado:
```bash
php artisan redis:test
```

Este comando vai:
1. Testar conectividade com Redis
2. Armazenar e recuperar dados de teste
3. Mostrar dados armazenados
4. Informar se tudo está funcionando

## 🎯 Benefícios Implementados

1. **Sessões no Redis**: Mais rápidas que database
2. **Cache de Usuário**: CPF/CNPJ verificados via Redis
3. **Menos Queries**: Validações não consultam MySQL
4. **Escalabilidade**: Redis suporta muitos clientes simultâneos
5. **TTL Automático**: Dados expiram em 24h automaticamente

## 📝 Controllers Atualizados

- `AuthController`: Cacheia após login, registro, atualização
- `GoogleAuthController`: Cacheia após autenticação Google
- `TrabalhoFeitoController`: Validações usam UserSessionService
- `DashboardController`: Validações usam UserSessionService

## 🚀 Como Usar em Novo Código

Sempre que precisar validar dados de usuário:

```php
// Em vez de:
if (empty(auth()->user()->CPF) && empty(auth()->user()->CNPJ)) {
    // vai bater no DB
}

// Use:
if (!UserSessionService::hasCpfOrCnpj(auth()->id())) {
    // vai verificar no Redis (muito mais rápido)
}
```

## ⚠️ Importante

- Redis precisa estar rodando (Docker compose configurado)
- Predis foi instalado como dependência
- O cache expira em 24h automaticamente
- Se atualizar usuário no DB, chamar: `UserSessionService::cacheUserData($updatedUser)`

## 📦 Dependências Adicionadas

```
predis/predis: ^3.4
```

Instalado via: `composer require predis/predis`
