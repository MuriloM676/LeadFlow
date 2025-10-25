# Comandos Úteis - CRM Vendas

## Instalação Inicial

```bash
# 1. Instalar Composer (se não tiver)
# Baixar de: https://getcomposer.org/Composer-Setup.exe

# 2. Instalar dependências PHP
composer install

# 3. Instalar Filament
composer require filament/filament:"^3.0-stable" -W
php artisan filament:install --panels

# 4. Configurar ambiente
copy .env.example .env
php artisan key:generate

# 5. Iniciar banco de dados
docker-compose up -d

# 6. Aguardar MySQL inicializar (10 segundos)
timeout /t 10

# 7. Rodar migrations e seeders
php artisan migrate --seed

# 8. Instalar dependências Node.js
npm install

# 9. Compilar assets (em outro terminal)
npm run dev

# 10. Iniciar servidor Laravel
php artisan serve
```

## Acessar o Sistema

URL: http://localhost:8000/admin

**Credenciais de Teste:**
- Gestor: gestor@example.com / password
- Vendedor: vendedor@example.com / password

## Comandos de Desenvolvimento

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rodar migrations
php artisan migrate
php artisan migrate:fresh --seed  # Resetar banco

# Criar novo usuário admin
php artisan make:filament-user

# Rodar testes
php artisan test
php artisan test --filter=LeadPermissionsTest

# Verificar rotas
php artisan route:list

# Tinker (console interativo)
php artisan tinker
```

## Comandos Docker

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs do MySQL
docker logs crm_vendas_mysql

# Entrar no container MySQL
docker exec -it crm_vendas_mysql mysql -ucrm_user -pcrm_password crm_vendas
```

## Estrutura de Arquivos Importantes

```
app/
├── Filament/
│   ├── Resources/      # CRUD do Filament
│   └── Widgets/        # Widgets do Dashboard
├── Models/             # Models Eloquent
└── Policies/           # Controle de acesso

database/
├── migrations/         # Estrutura do banco
├── seeders/           # Dados iniciais
└── factories/         # Fábricas de dados

routes/
└── web.php            # Rotas web

resources/
└── views/
    └── filament/      # Views personalizadas
```

## Resolver Problemas Comuns

### Erro: "Class 'Filament\Panel' not found"
```bash
composer require filament/filament:"^3.0-stable" -W
php artisan filament:install --panels
```

### Erro: "Connection refused" ao conectar no MySQL
```bash
# Verificar se container está rodando
docker ps

# Reiniciar container
docker-compose restart

# Verificar porta no .env (deve ser 3307)
```

### Erro de permissão em storage/
```bash
# Windows (executar como Admin)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

### Erro: "npm not found"
```bash
# Instalar Node.js de: https://nodejs.org/
# Depois executar:
npm install
```

## Criar Novos Componentes

```bash
# Criar novo Resource
php artisan make:filament-resource NomeDoModelo

# Criar novo Widget
php artisan make:filament-widget NomeWidget

# Criar novo Model
php artisan make:model NomeDoModelo -mf
# -m = migration
# -f = factory

# Criar Policy
php artisan make:policy NomePolicy --model=NomeDoModelo

# Criar Seeder
php artisan make:seeder NomeSeeder
```

## Backup e Restauração

```bash
# Exportar dados
docker exec crm_vendas_mysql mysqldump -ucrm_user -pcrm_password crm_vendas > backup.sql

# Importar dados
docker exec -i crm_vendas_mysql mysql -ucrm_user -pcrm_password crm_vendas < backup.sql
```

## Produção

```bash
# Compilar assets para produção
npm run build

# Otimizar aplicação
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Desabilitar debug no .env
APP_ENV=production
APP_DEBUG=false
```
