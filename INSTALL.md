# Guia de Instalação Detalhado - CRM Vendas

## Passo 1: Instalação do Composer

O Composer é necessário para gerenciar as dependências do Laravel.

### Windows:
1. Baixe o instalador em: https://getcomposer.org/Composer-Setup.exe
2. Execute o instalador e siga as instruções
3. Reinicie o terminal após a instalação

### Verifique a instalação:
```bash
composer --version
```

## Passo 2: Instalação do Laravel

Com o Composer instalado, execute na pasta do projeto:

```bash
# Instalar dependências do Laravel
composer install

# Ou se preferir criar um projeto Laravel limpo e copiar os arquivos:
composer create-project laravel/laravel .
```

## Passo 3: Instalação do Filament

```bash
composer require filament/filament:"^3.0-stable" -W
php artisan filament:install --panels
```

## Passo 4: Configuração do Ambiente

```bash
# Copiar arquivo de configuração
copy .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

## Passo 5: Banco de Dados

```bash
# Iniciar MySQL com Docker (já feito)
docker-compose up -d

# Aguardar alguns segundos para o MySQL inicializar
timeout /t 10

# Executar migrations
php artisan migrate

# Popular banco com dados de teste
php artisan db:seed
```

## Passo 6: Criar Usuário Admin do Filament

```bash
php artisan make:filament-user
```

Ou use os usuários criados pelo seeder:
- **Gestor**: gestor@example.com / password
- **Vendedor**: vendedor@example.com / password

## Passo 7: Compilar Assets

```bash
npm install
npm run dev
```

## Passo 8: Iniciar Servidor

```bash
php artisan serve
```

Acesse: http://localhost:8000/admin

## Estrutura Criada

O sistema incluirá automaticamente:

### Models e Migrations
- User (com roles)
- Lead
- PipelineStage
- Activity
- Product
- Opportunity

### Filament Resources
- LeadResource (com Kanban board)
- PipelineStageResource
- ActivityResource
- ProductResource
- UserResource

### Dashboards
- SellerDashboard (para vendedores)
- ManagerDashboard (para gestores)

### Policies
- LeadPolicy
- ActivityPolicy
- OpportunityPolicy

### Widgets
- ActiveLeadsWidget
- PipelineValueWidget
- ConversionRateWidget
- OverdueActivitiesWidget

## Solução de Problemas

### Erro de conexão com MySQL
- Verifique se o container está rodando: `docker ps`
- Verifique as configurações no .env (porta 3307)

### Erro ao executar migrations
- Aguarde alguns segundos após iniciar o Docker
- Verifique se o MySQL está pronto: `docker logs crm_vendas_mysql`

### Erro de permissões
```bash
# Windows (executar como administrador se necessário)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

## Próximos Passos

Após a instalação, o sistema estará pronto com:
- ✅ Autenticação configurada
- ✅ Permissões de vendedor e gestor
- ✅ Módulo de leads completo
- ✅ Pipeline configurável
- ✅ Sistema de atividades
- ✅ Catálogo de produtos
- ✅ Dashboards personalizados
- ✅ Dados de exemplo

Bom uso do CRM Vendas! 🚀
