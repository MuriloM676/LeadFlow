# GitHub Actions - Workflows Documentation

Este documento descreve os workflows de CI/CD implementados para o projeto LeadFlow.

## 📋 Workflows Disponíveis

### 1. CI - Tests and Code Quality (`ci.yml`)

**Trigger:** Push ou Pull Request nas branches `main` e `develop`

**Funcionalidades:**
- ✅ Testes automatizados em múltiplas versões do PHP (8.2, 8.3, 8.4)
- ✅ Cobertura de código (mínimo 80%)
- ✅ Análise estática com PHPStan
- ✅ Verificação de estilo com Laravel Pint
- ✅ Auditoria de segurança de dependências
- ✅ Build da imagem Docker

**Services:**
- MySQL 8.0
- Redis 7

**Ambiente:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=crm_vendas_test
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

### 2. Deploy to Production (`deploy.yml`) — Desabilitado

Este workflow foi desabilitado conforme o requisito: não realizar deploy em servidor de produção. O arquivo permanece apenas como referência histórica e não executa nenhuma ação.

---

### 3. Docker Build and Push (`docker.yml`)

**Trigger:**
- Push na branch `main`
- Tags no formato `v*.*.*`
- Pull Requests

**Funcionalidades:**
- 🐳 Build de imagens Docker multi-arquitetura (amd64, arm64)
- 📦 Push para GitHub Container Registry (ghcr.io)
- 🔍 Scan de vulnerabilidades com Trivy
- 🏷️ Versionamento automático de tags
- ⚡ Cache otimizado para builds rápidos

**Tags Geradas:**
- `main` → `latest`
- `v1.2.3` → `1.2.3`, `1.2`, `1`, `latest`
- PRs → `pr-123`
- Commits → `main-sha123456`

---

### 4. Security Audit (`security.yml`)

**Trigger:**
- Toda segunda-feira à meia-noite (cron)
- Push na branch `main`
- Manual (workflow_dispatch)

**Funcionalidades:**
- 🔒 Scan de vulnerabilidades em dependências PHP
- 📊 Análise de código com CodeQL
- 🔍 Review de dependências em PRs
- 📈 Upload de resultados para GitHub Security

---

### 5. Database Backup (`backup.yml`) — Desabilitado

Workflow de backup desabilitado (sem operações em produção).

---

## 🔧 Configuração Inicial

### 1. Configurar Secrets no GitHub

Acesse: `Settings` → `Secrets and variables` → `Actions`

Não há secrets obrigatórios, pois deploy e backup estão desativados.

### 2. Habilitar Environments (Opcional)

Para adicionar proteções de deploy:

1. Acesse `Settings` → `Environments`
2. Crie environment `production`
3. Configure:
   - Required reviewers (aprovação manual)
   - Wait timer (delay antes do deploy)
   - Deployment branches (apenas `main`)

### 3. Habilitar GitHub Container Registry

As imagens Docker são publicadas em `ghcr.io`. Para usar:

```bash
# Login
echo $GITHUB_TOKEN | docker login ghcr.io -u USERNAME --password-stdin

# Pull image
docker pull ghcr.io/murilom676/leadflow:latest
```

### 4. Configurar Code Coverage (Opcional)

Para relatórios de cobertura em [Codecov](https://codecov.io):

1. Acesse codecov.io e conecte o repositório
2. Adicione secret `CODECOV_TOKEN` (se repo privado)

---

## 🚀 Como Usar

Deploy e backup manuais foram removidos do fluxo.

### Verificar Testes Localmente

```bash
# Rodar todos os testes
php artisan test

# Com cobertura
php artisan test --coverage --min=80

# PHPStan
./vendor/bin/phpstan analyse

# Laravel Pint
./vendor/bin/pint --test
```

---

## 📊 Status Badges

Adicione ao README.md:

```markdown
![CI Tests](https://github.com/MuriloM676/leadflow/workflows/CI%20-%20Tests%20and%20Code%20Quality/badge.svg)
<!-- Deploy badge removido pois o workflow está desabilitado -->
![Security](https://github.com/MuriloM676/leadflow/workflows/Security%20Audit/badge.svg)
```

---

## 🔒 Segurança

### Proteções Implementadas

- ✅ Secrets criptografados
- ✅ Scan de vulnerabilidades (Trivy, Composer Audit)
- ✅ CodeQL análise de código
- ✅ Dependency review automático
<!-- Environments e backups removidos do escopo atual -->

### Boas Práticas

1. **Nunca commitar secrets** - Use GitHub Secrets
2. **Revisar PRs antes de merge** - CI valida automaticamente
3. **Monitorar security alerts** - GitHub notifica vulnerabilidades
4. **Manter dependências atualizadas** - Composer audit semanal
5. **Testar localmente antes do push** - Evita falhas no CI

---

## 🐛 Troubleshooting

### CI Failing - Tests

```bash
# Verificar localmente
docker compose exec app php artisan test

# Limpar cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

### Deploy Failing - SSH

```bash
# Testar conexão SSH
ssh -i ~/.ssh/id_rsa user@server

# Verificar permissões da chave
chmod 600 ~/.ssh/id_rsa
```

### Docker Build Failing

```bash
# Build local
docker compose build --no-cache

# Verificar logs
docker compose logs app
```

### Backup Failing

```bash
# Testar backup manual
docker compose exec mysql mysqldump -u root -p crm_vendas > backup.sql

# Verificar espaço em disco
df -h
```

---

## 📚 Recursos

- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)
- [Filament Optimization](https://filamentphp.com/docs/3.x/panels/optimization)

---

## 📝 Próximas Melhorias

- [ ] Staging environment automático
- [ ] Performance testing (Lighthouse CI)
- [ ] E2E testing (Dusk)
- [ ] Auto-release notes
- [ ] Slack notifications mais detalhadas
- [ ] Rollback automático em caso de falha
- [ ] Health checks pós-deploy
- [ ] Database seeding automático em staging

---

**Desenvolvido para LeadFlow CRM** 🚀
