# 🚀 CI/CD Quick Start Guide

## Primeiros Passos

### 1️⃣ Configurar Secrets do GitHub (Opcional)

Para os workflows atuais (CI, Segurança e Build de Docker), não há secrets obrigatórios. 
Opcionalmente, você pode configurar:

```yaml
# Codecov para cobertura (apenas se o repositório for privado)
CODECOV_TOKEN

# Registro de container (se optar por publicar imagens em ghcr.io)
GITHUB_TOKEN  # Já disponível por padrão no Actions
```

---

## 2️⃣ (Sem Servidor de Produção)

Este projeto não realiza deploy em servidor de produção via Actions. Os fluxos atuais são focados em CI, qualidade de código e build/publish de imagens Docker.

---

## 3️⃣ Workflows Disponíveis

### ✅ CI - Tests and Code Quality
**Quando executa:** Em todo push/PR nas branches `main` e `develop`

**O que faz:**
- Roda testes em PHP 8.2, 8.3 e 8.4
- Verifica estilo de código (Pint)
- Análise estática (PHPStan)
- Auditoria de segurança
- Build da imagem Docker

**Como ver:** [Actions → CI](https://github.com/MuriloM676/leadflow/actions)

### 🚀 Deploy
Não há deploy para servidor configurado. Como alternativa, o workflow de Docker pode publicar imagens em `ghcr.io` para uso posterior.

### 🐳 Docker Build and Push
**Quando executa:** Push na `main` ou criação de tag

**O que faz:**
- Build de imagens Docker multi-arquitetura
- Push para GitHub Container Registry
- Scan de vulnerabilidades (Trivy)
- Versionamento automático

**Usar a imagem:**
```bash
docker pull ghcr.io/murilom676/leadflow:latest
```

### 🔒 Security Audit
**Quando executa:** 
- Toda segunda às 00:00
- Push na `main`
- Manualmente

**O que faz:**
- Scan de vulnerabilidades em dependências
- Análise de código (CodeQL)
- Review de dependências em PRs

### 💾 Backup
Sem rotinas de backup via Actions neste escopo.

---

## 4️⃣ Fluxo de Trabalho Recomendado

### Desenvolvimento
```bash
# 1. Criar branch
git checkout -b feature/nova-funcionalidade

# 2. Desenvolver e testar localmente
./vendor/bin/pint          # Formatar código
./vendor/bin/phpstan analyse  # Análise estática
php artisan test           # Rodar testes

# 3. Commit e push
git add .
git commit -m "feat: adiciona nova funcionalidade"
git push origin feature/nova-funcionalidade

# 4. Criar Pull Request
# CI vai executar automaticamente
```

### Publicação de Imagens (Opcional)
Para publicar imagens Docker, crie uma tag ou faça push na branch `main` e habilite o workflow `Docker Build and Push` (ghcr.io).

---

## 5️⃣ Verificações Antes do Commit

Execute o script de verificação:
```bash
chmod +x .github/pre-commit-check.sh
./.github/pre-commit-check.sh
```

Ou manualmente:
```bash
# 1. Formatar código
./vendor/bin/pint

# 2. Análise estática
./vendor/bin/phpstan analyse

# 3. Testes
php artisan test

# 4. Auditoria de segurança
composer audit
```

---

## 6️⃣ Environments
Não é necessário configurar environments de produção para os workflows atuais.

---

## 7️⃣ Monitoramento

### Verificar Status dos Workflows
```bash
# Ver no GitHub
https://github.com/MuriloM676/leadflow/actions

# Via CLI
gh run list
gh run view [run-id]
```

### Logs em Tempo Real
```bash
# Ver logs do último workflow
gh run view --log

# Ver logs de um job específico
gh run view [run-id] --log --job=[job-id]
```

### Build de Docker
```bash
# Verificar execuções
https://github.com/MuriloM676/leadflow/actions

# Baixar a imagem publicada (se habilitado)
docker pull ghcr.io/murilom676/leadflow:latest
```

---

## 8️⃣ Troubleshooting

### CI falha com erro de teste
```bash
# Rodar localmente
docker compose exec app php artisan test --filter=NomeDoTeste

# Ver logs detalhados
docker compose logs app
```

### Problemas com publicação de Docker
```bash
# Build local
docker compose build --no-cache

# Limpar cache do Docker
docker builder prune -a
```

### Imagem Docker não builda
```bash
# Build local
docker compose build --no-cache

# Limpar cache do Docker
docker builder prune -a
```

### Backup falha
```bash
# Verificar espaço em disco no servidor
ssh user@server "df -h"

# Testar backup manual
docker compose exec mysql mysqldump -u root -p crm_vendas > test.sql
```

---

## 9️⃣ Comandos Úteis

```bash
# Cancelar workflow em execução
gh run cancel [run-id]

# Re-executar workflow falhado
gh run rerun [run-id]

# Executar workflow de Docker manualmente
gh workflow run "Docker Build and Push"

# Ver secrets configurados
gh secret list

# Atualizar secret
echo "new-value" | gh secret set SECRET_NAME
```

---

## 🔟 Checklist de CI

- [ ] Testes passando localmente
- [ ] Pint sem violações
- [ ] PHPStan sem erros críticos
- [ ] Auditoria de dependências revisada
- [ ] Cobertura de testes >= 80%

---

## 📚 Recursos

- [Documentação Completa](.github/WORKFLOWS.md)
- [GitHub Actions Docs](https://docs.github.com/actions)
- [Docker Hub](https://hub.docker.com)
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

## 🆘 Precisa de Ajuda?

1. Consulte a [documentação completa](.github/WORKFLOWS.md)
2. Verifique os [logs dos workflows](https://github.com/MuriloM676/leadflow/actions)
3. Abra uma [issue](https://github.com/MuriloM676/leadflow/issues)

---

**Bons testes e builds! 🚀**
