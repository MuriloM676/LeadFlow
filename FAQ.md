# FAQ e Troubleshooting - CRM Vendas

## Perguntas Frequentes

### 1. Como faço para instalar o sistema?

Siga os passos do arquivo `INSTALL.md` ou execute o script de setup:
```bash
setup.bat
```

### 2. Qual a diferença entre Vendedor e Gestor?

- **Vendedor**: Vê apenas seus próprios leads e atividades. Pode criar e gerenciar leads, mas não pode modificar usuários, produtos ou etapas do pipeline.
- **Gestor**: Tem acesso total ao sistema. Pode ver todos os leads, gerenciar usuários, produtos, etapas e gerar relatórios completos.

### 3. Como adicionar um novo usuário?

**Via Painel (apenas Gestor):**
1. Acesse: Admin → Usuários
2. Clique em "Criar"
3. Preencha nome, e-mail, senha e selecione a função
4. Salve

**Via Terminal:**
```bash
php artisan make:filament-user
```

### 4. Como funciona o Kanban board?

O Kanban exibe os leads organizados por etapas do pipeline. Você pode:
- Arrastar cards entre colunas para mudar a etapa do lead
- Visualizar informações rápidas do lead
- Ver alertas de atividades atrasadas
- Clicar em "Ver detalhes" para editar o lead completo

### 5. Como criar uma nova etapa no pipeline?

**Apenas Gestores podem:**
1. Acesse: Admin → Etapas do Pipeline
2. Clique em "Criar"
3. Defina nome, ordem e cor
4. Marque como "Ativo"
5. Salve

### 6. O que são Oportunidades?

Oportunidades são produtos/serviços vinculados a um lead com valor estimado. Um lead pode ter múltiplas oportunidades. O sistema calcula automaticamente o valor total.

### 7. Como sei se tenho atividades atrasadas?

- Dashboard exibe widget de "Atividades Atrasadas"
- Na listagem de leads, aparece um ícone de alerta vermelho
- Na listagem de atividades, há uma coluna "Atrasada"
- Use o filtro "Atrasadas" na página de atividades

### 8. Posso importar leads de outro sistema?

Atualmente não há importação automática, mas você pode:
1. Criar seeds customizados
2. Usar Tinker para inserir via código
3. Desenvolver um comando Artisan customizado
4. Usar a API (se implementada)

### 9. Como resetar o banco de dados?

```bash
php artisan migrate:fresh --seed
```
⚠️ **ATENÇÃO**: Isso apagará todos os dados!

### 10. Como fazer backup dos dados?

```bash
# Exportar
docker exec crm_vendas_mysql mysqldump -ucrm_user -pcrm_password crm_vendas > backup.sql

# Importar
docker exec -i crm_vendas_mysql mysql -ucrm_user -pcrm_password crm_vendas < backup.sql
```

## Problemas Comuns e Soluções

### ❌ Erro: "composer: command not found"

**Causa**: Composer não está instalado ou não está no PATH

**Solução**:
1. Baixe e instale: https://getcomposer.org/Composer-Setup.exe
2. Reinicie o terminal
3. Execute: `composer --version` para confirmar

---

### ❌ Erro: "SQLSTATE[HY000] [2002] Connection refused"

**Causa**: MySQL não está rodando ou configuração incorreta

**Soluções**:
1. Verifique se o container está rodando:
   ```bash
   docker ps
   ```

2. Se não estiver, inicie:
   ```bash
   docker-compose up -d
   ```

3. Aguarde 10-15 segundos para o MySQL inicializar

4. Verifique o .env:
   ```
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=crm_vendas
   DB_USERNAME=crm_user
   DB_PASSWORD=crm_password
   ```

---

### ❌ Erro: "Bind for 0.0.0.0:3306 failed: port is already allocated"

**Causa**: Porta 3306 já está em uso

**Solução**: O docker-compose já usa porta 3307. Se ainda der erro:
1. Verifique se não há outro MySQL rodando
2. Ou altere para outra porta no docker-compose.yml

---

### ❌ Erro: "Class 'Filament\Panel' not found"

**Causa**: Filament não está instalado ou instalação incompleta

**Solução**:
```bash
composer require filament/filament:"^3.0-stable" -W
php artisan filament:install --panels
```

---

### ❌ Erro: "The stream or file could not be opened: failed to open stream: Permission denied"

**Causa**: Permissões incorretas nas pastas storage e bootstrap/cache

**Solução Windows**:
```bash
# Executar como Administrador
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

---

### ❌ Página em branco após login

**Causas possíveis**:
1. Assets não compilados
2. Erro de cache
3. Configuração incorreta

**Soluções**:
```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Compilar assets
npm run dev

# Ou em produção
npm run build
```

---

### ❌ Erro: "npm: command not found"

**Causa**: Node.js não está instalado

**Solução**:
1. Baixe e instale: https://nodejs.org/
2. Reinicie o terminal
3. Execute: `npm --version` para confirmar
4. Instale dependências: `npm install`

---

### ❌ Widgets não aparecem no Dashboard

**Causas**:
1. Cache não limpo
2. Banco vazio
3. Permissões incorretas

**Soluções**:
```bash
# Limpar cache
php artisan cache:clear

# Popular banco
php artisan db:seed

# Verificar se usuário tem permissão
# Vendedor vê apenas widgets de seus dados
# Gestor vê todos os widgets
```

---

### ❌ Kanban não atualiza após arrastar

**Causas**:
1. JavaScript não carregado
2. Erro de Livewire
3. Permissões

**Soluções**:
1. Abra o console do navegador (F12) e veja erros
2. Limpe cache do navegador (Ctrl+Shift+Delete)
3. Verifique se você tem permissão para editar o lead
4. Recarregue a página completamente

---

### ❌ "Too few arguments to function" nos testes

**Causa**: Factories com dependências obrigatórias

**Solução**: Use factories completas ou passe parâmetros necessários:
```php
Lead::factory()->create([
    'pipeline_stage_id' => PipelineStage::factory()->create()->id,
]);
```

---

### ❌ Email não funciona

**Causa**: Configuração de email não está correta

**Solução**: 
Para desenvolvimento, o .env já usa Mailpit (simulador). 
Para produção, configure SMTP real no .env:
```
MAIL_MAILER=smtp
MAIL_HOST=seu-smtp-host
MAIL_PORT=587
MAIL_USERNAME=seu-email
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
```

---

### ❌ Drag and drop não funciona no Kanban

**Causas**:
1. JavaScript desabilitado
2. Navegador antigo
3. Erro no console

**Soluções**:
1. Use navegador moderno (Chrome, Firefox, Edge atualizados)
2. Habilite JavaScript
3. Verifique erros no console (F12)
4. Limpe cache e cookies

---

## Performance

### Sistema está lento

**Otimizações**:

```bash
# Cache de configuração
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoload
composer install --optimize-autoloader --no-dev

# Compilar assets para produção
npm run build
```

### Queries lentas

1. Adicione índices nas migrations
2. Use eager loading nos relacionamentos
3. Implemente paginação
4. Use cache para queries frequentes

---

## Desenvolvimento

### Como adicionar um novo campo ao Lead?

1. Crie migration:
```bash
php artisan make:migration add_campo_to_leads_table
```

2. No arquivo de migration:
```php
public function up()
{
    Schema::table('leads', function (Blueprint $table) {
        $table->string('novo_campo')->nullable();
    });
}
```

3. Execute migration:
```bash
php artisan migrate
```

4. Adicione ao Model Lead (fillable)
5. Adicione ao LeadResource (form e table)

### Como criar um novo widget?

```bash
php artisan make:filament-widget NomeWidget
```

Edite em: `app/Filament/Widgets/NomeWidget.php`

### Como customizar cores do Filament?

Edite `app/Providers/Filament/AdminPanelProvider.php`:
```php
->colors([
    'primary' => Color::Blue,
])
```

---

## Produção

### Checklist de Deploy

- [ ] `APP_ENV=production` no .env
- [ ] `APP_DEBUG=false` no .env
- [ ] Executar `php artisan config:cache`
- [ ] Executar `php artisan route:cache`
- [ ] Executar `php artisan view:cache`
- [ ] Executar `npm run build`
- [ ] Configurar HTTPS
- [ ] Configurar backup automático
- [ ] Configurar monitoramento
- [ ] Testar todas as funcionalidades

### Segurança em Produção

1. Use senhas fortes
2. Configure firewall no servidor
3. Use HTTPS obrigatório
4. Mantenha Laravel e dependências atualizados
5. Faça backups regulares
6. Monitore logs de erro
7. Implemente rate limiting

---

## Suporte Adicional

- Documentação Laravel: https://laravel.com/docs
- Documentação Filament: https://filamentphp.com/docs
- Issues do GitHub (se aplicável)
- Comunidade Laravel Brasil

---

**Última atualização**: Outubro 2025
