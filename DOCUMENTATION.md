# Documentação Técnica - CRM Vendas

## Visão Geral do Sistema

O CRM Vendas é um sistema completo de gestão de leads e pipeline de vendas desenvolvido com Laravel 11 e Filament 3. O sistema implementa controle de acesso baseado em roles, permitindo que vendedores gerenciem seus próprios leads enquanto gestores têm acesso completo ao sistema.

## Arquitetura do Sistema

### Stack Tecnológico
- **Backend**: Laravel 11
- **Admin Panel**: Filament 3
- **Banco de Dados**: MySQL 8.0 (Docker)
- **Frontend**: Tailwind CSS
- **Build Tool**: Vite

### Estrutura de Dados

#### Usuários (users)
- `id`: Identificador único
- `name`: Nome do usuário
- `email`: E-mail (único)
- `password`: Senha (hash)
- `role`: Função (vendedor/gestor)
- Relacionamentos:
  - `hasMany(Lead)`: Leads atribuídos ao usuário
  - `hasMany(Activity)`: Atividades criadas pelo usuário

#### Etapas do Pipeline (pipeline_stages)
- `id`: Identificador único
- `name`: Nome da etapa
- `order`: Ordem de exibição
- `color`: Cor hexadecimal
- `is_active`: Status ativo/inativo
- Relacionamentos:
  - `hasMany(Lead)`: Leads na etapa

#### Leads (leads)
- `id`: Identificador único
- `contact_name`: Nome do contato
- `company`: Nome da empresa
- `email`: E-mail do contato
- `phone`: Telefone
- `source`: Origem do lead (enum)
- `user_id`: Vendedor responsável (FK)
- `pipeline_stage_id`: Etapa atual (FK)
- `needs_summary`: Resumo da necessidade
- `first_contact_date`: Data do primeiro contato
- Relacionamentos:
  - `belongsTo(User)`: Vendedor responsável
  - `belongsTo(PipelineStage)`: Etapa atual
  - `hasMany(Activity)`: Atividades do lead
  - `hasMany(Opportunity)`: Oportunidades do lead

#### Atividades (activities)
- `id`: Identificador único
- `lead_id`: Lead relacionado (FK)
- `user_id`: Usuário responsável (FK)
- `type`: Tipo (call/meeting/message/email)
- `scheduled_at`: Data/hora agendada
- `notes`: Anotações
- `status`: Status (scheduled/completed/cancelled)
- Relacionamentos:
  - `belongsTo(Lead)`: Lead relacionado
  - `belongsTo(User)`: Usuário responsável

#### Produtos (products)
- `id`: Identificador único
- `name`: Nome do produto
- `description`: Descrição
- `price`: Preço unitário
- `is_active`: Status ativo/inativo
- Relacionamentos:
  - `hasMany(Opportunity)`: Oportunidades com este produto

#### Oportunidades (opportunities)
- `id`: Identificador único
- `lead_id`: Lead relacionado (FK)
- `product_id`: Produto relacionado (FK)
- `quantity`: Quantidade
- `estimated_value`: Valor estimado
- `notes`: Observações
- Relacionamentos:
  - `belongsTo(Lead)`: Lead relacionado
  - `belongsTo(Product)`: Produto relacionado

## Controle de Acesso (Policies)

### LeadPolicy
- **Vendedor**: Pode ver e editar apenas seus próprios leads
- **Gestor**: Pode ver e editar todos os leads
- **Exclusão**: Apenas gestores podem excluir leads

### ActivityPolicy
- **Vendedor**: Pode ver e gerenciar atividades dos seus leads
- **Gestor**: Pode ver e gerenciar todas as atividades

### PipelineStagePolicy
- **Vendedor**: Pode visualizar etapas
- **Gestor**: Pode criar, editar e excluir etapas

### ProductPolicy
- **Vendedor**: Pode visualizar produtos
- **Gestor**: Pode criar, editar e excluir produtos

### UserPolicy
- **Gestor**: Controle total sobre usuários

## Recursos do Filament

### LeadResource
**Formulário:**
- Seção "Informações do Contato": nome, empresa, e-mail, telefone
- Seção "Detalhes do Lead": origem, responsável, etapa, data de contato, resumo

**Tabela:**
- Colunas: contato, empresa, e-mail, telefone, origem, responsável, etapa, valor total, status de atraso
- Filtros: por etapa, responsável (apenas gestor), origem
- Ordenação: por todas as colunas principais
- Busca: por nome, empresa, e-mail

**Páginas:**
- `ListLeads`: Listagem com filtros e busca
- `CreateLead`: Criação de novo lead
- `EditLead`: Edição de lead existente
- `KanbanLeads`: Visualização Kanban com drag-and-drop

### PipelineStageResource
**Funcionalidades:**
- Gerenciamento de etapas do pipeline
- Definição de ordem e cores
- Ativação/desativação de etapas
- Contagem de leads por etapa

### ActivityResource
**Funcionalidades:**
- Cadastro de atividades (chamada, reunião, mensagem, e-mail)
- Agendamento com data/hora
- Status tracking (agendada, concluída, cancelada)
- Detecção de atividades atrasadas
- Filtros por tipo, status e atraso

### ProductResource
**Funcionalidades:**
- Catálogo de produtos/serviços
- Precificação
- Descrições detalhadas
- Status ativo/inativo
- Contagem de oportunidades por produto

### UserResource
**Funcionalidades (apenas gestores):**
- Criação de usuários vendedores e gestores
- Gerenciamento de credenciais
- Visualização de leads por usuário

## Widgets do Dashboard

### ActiveLeadsWidget
- Total de leads ativos
- Novos leads do mês
- Gráfico de tendência

### PipelineValueWidget
- Valor total do pipeline
- Valor médio por lead
- Soma de todas as oportunidades

### ConversionRateWidget
- Taxa de conversão (%)
- Leads ganhos vs total
- Leads perdidos

### OverdueActivitiesWidget
- Tabela de atividades atrasadas
- Ação rápida para concluir
- Filtrada por usuário (vendedor vê apenas suas)

### LatestActivitiesWidget
- Últimas 10 atividades
- Todas com status e tipo
- Ordenadas por data

### LeadsByStageChart
- Gráfico de barras
- Distribuição de leads por etapa
- Atualizado em tempo real

## Kanban Board

O sistema implementa um board Kanban completo para gestão visual do pipeline:

**Funcionalidades:**
- Colunas representando etapas do pipeline
- Cards de leads com informações principais
- Drag-and-drop entre colunas
- Atualização automática no banco de dados
- Indicador visual de atividades atrasadas
- Valor total da oportunidade em destaque
- Link direto para edição do lead

**Implementação:**
- View Blade customizada
- JavaScript vanilla para drag-and-drop
- Livewire para comunicação com backend
- Permissões respeitadas (vendedor vê apenas seus leads)

## Seeders e Dados de Teste

O sistema inclui seeders completos que criam:
- 1 usuário gestor padrão
- 3 usuários vendedores específicos
- 5 vendedores aleatórios
- 6 etapas de pipeline padrão
- 15 produtos (5 específicos + 10 aleatórios)
- 50 leads distribuídos entre vendedores e etapas
- 1-3 atividades por lead
- Oportunidades para 70% dos leads
- 10 atividades atrasadas para teste

## Testes Automatizados

### LeadPermissionsTest
Testa as seguintes funcionalidades:
1. Vendedor pode criar lead
2. Vendedor vê apenas seus próprios leads
3. Gestor vê todos os leads
4. Lead pode ter atividades
5. Lead pode ter oportunidades
6. Sistema identifica atividades atrasadas
7. Atividades podem ser concluídas

Execute com: `php artisan test`

## Fluxo de Trabalho Típico

### Para Vendedor:
1. Login no sistema
2. Visualiza dashboard com suas métricas
3. Cria novo lead ou importa de fonte externa
4. Move lead pelo pipeline (Kanban ou edição direta)
5. Agenda atividades para o lead
6. Cria oportunidades vinculando produtos
7. Completa atividades conforme realiza
8. Move lead para "Fechado Ganho" ou "Fechado Perdido"

### Para Gestor:
1. Login no sistema
2. Visualiza dashboard consolidado de toda equipe
3. Monitora atividades atrasadas
4. Analisa taxa de conversão e valor do pipeline
5. Gerencia etapas do pipeline conforme necessário
6. Gerencia catálogo de produtos
7. Cria e gerencia usuários vendedores
8. Reatribui leads entre vendedores se necessário

## Extensibilidade

O sistema foi desenvolvido seguindo boas práticas do Laravel e Filament, facilitando:
- Adição de novos campos aos models
- Criação de novos recursos Filament
- Implementação de novos widgets
- Integração com APIs externas
- Customização de views
- Adição de novos tipos de relatórios

## Segurança

- Senhas hasheadas com bcrypt
- Proteção CSRF em formulários
- Validação de dados em todos os inputs
- Policies para controle de acesso granular
- Session-based authentication
- SQL injection prevention via Eloquent ORM

## Performance

- Queries otimizadas com eager loading
- Índices em colunas de busca frequente
- Cache de configurações (produção)
- Assets minificados (produção)
- Conexão persistente com banco de dados

## Backup e Manutenção

Ver arquivo `COMMANDS.md` para instruções de:
- Backup do banco de dados
- Restauração de dados
- Limpeza de cache
- Otimização para produção
- Comandos úteis de desenvolvimento
