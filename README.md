# 📊 CRM Vendas - Sistema de Gestão de Leads e Pipeline

> Sistema completo de CRM desenvolvido com **Laravel 11** e **Filament 3** para gestão de leads e pipeline de vendas.

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3-F59E0B?style=flat)](https://filamentphp.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)](https://www.mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker)](https://www.docker.com)
[![CI Tests](https://github.com/MuriloM676/leadflow/workflows/CI%20-%20Tests%20and%20Code%20Quality/badge.svg)](https://github.com/MuriloM676/leadflow/actions)
[![Security](https://github.com/MuriloM676/leadflow/workflows/Security%20Audit/badge.svg)](https://github.com/MuriloM676/leadflow/actions)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

---

## 🎯 O que é este projeto?

Um CRM (Customer Relationship Management) completo e funcional focado em **gestão de leads** e **pipeline de vendas**. Perfeito para equipes comerciais que precisam organizar contatos, gerenciar oportunidades e acompanhar métricas de vendas.

### ✨ Destaques

- 🎨 **Interface moderna** com Filament 3
- 🔐 **Sistema de permissões** (Vendedor/Gestor)
- 📊 **Dashboard interativo** com métricas em tempo real
- 🔄 **Kanban Board** com drag-and-drop
- ⚡ **Alertas inteligentes** de atividades atrasadas
- 📦 **Pronto para usar** com dados de exemplo
- 🧪 **Testes automatizados**
- 📖 **Documentação completa**

---

## 🚀 Funcionalidades

- **Autenticação e Permissões**: Sistema com roles separando vendedores e gestores
- **Gestão de Leads**: Cadastro completo de leads com informações de contato
- **Pipeline de Vendas**: Etapas configuráveis com drag-and-drop
- **Atividades**: Registro de chamadas, reuniões e mensagens com alertas de atraso
- **Produtos**: Catálogo de produtos/serviços vinculáveis a oportunidades
- **Oportunidades**: Valores estimados vinculados aos leads
- **Dashboards**: Painéis separados para vendedores e gestores com métricas
- **Relatórios**: Taxa de conversão, valor total do pipeline e análises

---

## 📋 Pré-requisitos

Antes de começar, você precisa ter instalado:

- ✅ **PHP 8.2+** - [Download](https://www.php.net/downloads)
- ✅ **Composer** - [Download](https://getcomposer.org/)
- ✅ **Docker Desktop** - [Download](https://www.docker.com/products/docker-desktop)
- ✅ **Node.js 18+** - [Download](https://nodejs.org/)

---

## 🔧 Instalação Rápida

### Opção 1: Script Automático (Recomendado)

```bash
# Execute o script de setup
setup.bat
```

### Opção 2: Instalação Manual

```bash
# 1. Instalar dependências
composer install

# 2. Instalar Filament
composer require filament/filament:"^3.0-stable" -W
php artisan filament:install --panels

# 3. Configurar ambiente
copy .env.example .env
php artisan key:generate

# 4. Iniciar MySQL com Docker
docker-compose up -d

# 5. Aguardar MySQL inicializar
timeout /t 15

# 6. Executar migrations e popular banco
php artisan migrate --seed

# 7. Instalar dependências frontend
npm install

# 8. Iniciar servidor (novo terminal)
php artisan serve

# 9. Compilar assets (outro terminal)
npm run dev
```

---

## 🌐 Acessando o Sistema

Após a instalação, acesse:

**URL**: http://localhost:8000/admin

### 👤 Credenciais de Teste

**Gestor:**
- E-mail: `gestor@example.com`
- Senha: `password`

**Vendedor:**
- E-mail: `vendedor@example.com`
- Senha: `password`

---

## 🗄️ Banco de Dados

O sistema utiliza MySQL 8.0 rodando em Docker:

| Parâmetro | Valor |
|-----------|-------|
| Host | 127.0.0.1 |
| Porta | 3307 |
| Database | crm_vendas |
| Usuário | crm_user |
| Senha | crm_password |

---

## 🏗️ Estrutura do Sistema

### 📦 Módulos Principais

| Módulo | Descrição |
|--------|-----------|
| **Leads** | Gestão completa de leads com pipeline |
| **Pipeline** | Etapas configuráveis com drag-and-drop |
| **Atividades** | Chamadas, reuniões e follow-ups |
| **Produtos** | Catálogo de produtos/serviços |
| **Oportunidades** | Valores estimados por lead |
| **Usuários** | Gestão de vendedores e gestores |

### 🔐 Permissões

<table>
<tr>
<th>Funcionalidade</th>
<th>Vendedor</th>
<th>Gestor</th>
</tr>
<tr>
<td>Visualizar próprios leads</td>
<td>✅</td>
<td>✅</td>
</tr>
<tr>
<td>Visualizar todos os leads</td>
<td>❌</td>
<td>✅</td>
</tr>
<tr>
<td>Criar/Editar leads</td>
<td>✅ (próprios)</td>
<td>✅ (todos)</td>
</tr>
<tr>
<td>Deletar leads</td>
<td>❌</td>
<td>✅</td>
</tr>
<tr>
<td>Gerenciar pipeline</td>
<td>❌</td>
<td>✅</td>
</tr>
<tr>
<td>Gerenciar produtos</td>
<td>❌</td>
<td>✅</td>
</tr>
<tr>
<td>Gerenciar usuários</td>
<td>❌</td>
<td>✅</td>
</tr>
<tr>
<td>Dashboard completo</td>
<td>✅ (próprios dados)</td>
<td>✅ (todos os dados)</td>
</tr>
</table>

---

## 📊 Dashboard

Execute os testes automatizados:
```bash
php artisan test
```

## 📊 Dashboard

O dashboard exibe métricas em tempo real:

- 📈 **Total de leads ativos**
- 💰 **Valor total do pipeline**
- 🎯 **Taxa de conversão**
- ⚠️ **Leads com atividades atrasadas**
- 📅 **Últimas atividades**
- 📊 **Gráficos de desempenho**

> Vendedores veem apenas suas métricas. Gestores veem métricas de toda a equipe.

---

## 🧪 Testes Automatizados

Execute os testes com:

```bash
php artisan test
```

**Cobertura de testes:**
- ✅ Criação de leads
- ✅ Permissões de acesso
- ✅ Relacionamentos (atividades e oportunidades)
- ✅ Detecção de atividades atrasadas
- ✅ Conclusão de atividades

---

## 📚 Documentação Completa

Este projeto inclui documentação detalhada:

| Arquivo | Descrição |
|---------|-----------|
| [README.md](README.md) | Visão geral (você está aqui) |
| [INSTALL.md](INSTALL.md) | Guia de instalação passo a passo |
| [DOCUMENTATION.md](DOCUMENTATION.md) | Documentação técnica completa |
| [COMMANDS.md](COMMANDS.md) | Comandos úteis e referência |
| [FAQ.md](FAQ.md) | Perguntas frequentes e troubleshooting |
| [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) | Sumário executivo do projeto |
| [CHECKLIST.md](CHECKLIST.md) | Checklist de verificação |

---

## 🛠️ Tecnologias Utilizadas


| Tecnologia | Versão | Uso |
|------------|--------|-----|
| PHP | 8.2+ | Backend |
| Laravel | 11 | Framework |
| Filament | 3 | Admin Panel |
| MySQL | 8.0 | Banco de Dados |
| Docker | Latest | Containerização |
| Tailwind CSS | 3 | Estilos |
| Alpine.js | Via Filament | Interatividade |
| Livewire | Via Filament | Componentes reativos |

---

## 🎨 Screenshots

### Dashboard
![Dashboard](https://via.placeholder.com/800x400/3b82f6/ffffff?text=Dashboard+com+M%C3%A9tricas)

### Visualização Kanban
![Kanban](https://via.placeholder.com/800x400/10b981/ffffff?text=Kanban+Board+com+Drag-and-Drop)

### Gestão de Leads
![Leads](https://via.placeholder.com/800x400/f59e0b/ffffff?text=Lista+de+Leads+com+Filtros)

---

## 💡 Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear

# Resetar banco de dados
php artisan migrate:fresh --seed

# Criar novo usuário admin
php artisan make:filament-user

# Rodar testes
php artisan test

# Compilar para produção
npm run build
```

Para mais comandos, consulte [COMMANDS.md](COMMANDS.md)

---

## � Problemas Comuns

### MySQL não conecta
```bash
# Verificar se o container está rodando
docker ps

# Reiniciar container
docker-compose restart
```

### Erro de permissões
```bash
# Windows (executar como Admin)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

Para mais soluções, consulte [FAQ.md](FAQ.md)

---

## 🚀 Próximos Passos (Sugestões)

- [ ] Relatórios em PDF
- [ ] Exportação para Excel
- [ ] API REST
- [ ] Notificações por e-mail
- [ ] Calendário de atividades
- [ ] Integração com WhatsApp
- [ ] App mobile

---

## 📄 Licença

Este projeto é de código aberto e está disponível para uso livre.

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para:

1. Fork o projeto
2. Criar uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abrir um Pull Request

---

## 👨‍💻 Sobre

Desenvolvido com ❤️ usando Laravel e Filament.

**Stack:**
- 🐘 Laravel Framework
- 🎨 Filament PHP
- 🗄️ MySQL Database
- 🐳 Docker

---

## 📞 Suporte e Links Úteis

- 📖 [Documentação Laravel](https://laravel.com/docs)
- 📖 [Documentação Filament](https://filamentphp.com/docs)
- 🌐 [Laravel Brasil](https://laravelbrasil.com.br)
- 💬 [Comunidade Filament](https://filamentphp.com/discord)

---

<div align="center">

**⭐ Se este projeto foi útil, considere dar uma estrela!**

Made with ❤️ using Laravel & Filament

**Versão 1.0.0** | **Outubro 2025**

</div>

