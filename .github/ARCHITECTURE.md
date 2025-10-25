# 🏗️ CI/CD Architecture

## 📊 Visão Geral do Pipeline

```
┌─────────────────────────────────────────────────────────────────┐
│                         DEVELOPMENT                              │
└─────────────────────────────────────────────────────────────────┘
                                ↓
                    ┌──────────────────────┐
                    │   Git Push / PR      │
                    └──────────────────────┘
                                ↓
┌─────────────────────────────────────────────────────────────────┐
│                    CONTINUOUS INTEGRATION                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────┐  ┌──────────────────┐  ┌──────────────┐   │
│  │  Code Quality   │  │  Security Scan   │  │  Tests PHP   │   │
│  │  (Pint/PHPStan) │  │  (Composer Audit)│  │  8.2/8.3/8.4 │   │
│  └─────────────────┘  └──────────────────┘  └──────────────┘   │
│           ↓                    ↓                     ↓          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              Build Docker Image                         │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                                ↓
                    ┌──────────────────────┐
                    │   Merge to main      │
                    └──────────────────────┘
                                ↓
┌─────────────────────────────────────────────────────────────────┐
│                  ARTIFACT DEPLOYMENT (IMAGENS)                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  1. Build de Imagem Docker                             │   │
│  │  2. Tag e Metadados                                    │   │
│  │  3. (Opcional) Push para ghcr.io                       │   │
│  │  4. Scan de Vulnerabilidades (Trivy)                   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Fluxo de Workflows

### 1. CI - Tests and Code Quality

```
Push/PR
  │
  ├─→ Setup PHP (8.2, 8.3, 8.4)
  │     │
  │     ├─→ Install Dependencies
  │     │     │
  │     │     ├─→ Run Tests (Parallel + Coverage)
  │     │     │     ├─→ ✅ Pass → Continue
  │     │     │     └─→ ❌ Fail → Block PR
  │     │     │
  │     │     ├─→ Code Style (Pint)
  │     │     │     ├─→ ✅ Pass → Continue
  │     │     │     └─→ ❌ Fail → Block PR
  │     │     │
  │     │     ├─→ Static Analysis (PHPStan)
  │     │     │     ├─→ ✅ Pass → Continue
  │     │     │     └─→ ⚠️  Warning → Log
  │     │     │
  │     │     └─→ Security Audit
  │     │           ├─→ ✅ No Issues → Continue
  │     │           └─→ ⚠️  Vulnerabilities → Log
  │     │
  │     └─→ Upload Coverage to Codecov
  │
  └─→ Build Docker Image (if main branch)
        ├─→ ✅ Success → Cache Image
        └─→ ❌ Fail → Notify
```

### 2. Artifact Deployment (Imagens Docker)

```
Push to main / Create Tag (Opcional)
  │
  ├─→ Construir e Publicar imagem em ghcr.io
  │     ├─→ Tags automáticas (semver, sha, latest)
  │     ├─→ Multi-arch (amd64, arm64)
  │     └─→ Scan (Trivy) + Upload para Security Tab
```

### 3. Docker Build and Push

```
Push to main / Tag
  │
  ├─→ Extract Metadata (tags, labels)
  │     │
  │     ├─→ Build Multi-Platform Image
  │     │     ├─→ linux/amd64
  │     │     └─→ linux/arm64
  │     │
  │     ├─→ Push to GHCR (ghcr.io)
  │     │     ├─→ latest
  │     │     ├─→ v1.2.3
  │     │     └─→ sha-abc123
  │     │
  │     ├─→ Scan with Trivy
  │     │     ├─→ ✅ No Vulnerabilities
  │     │     └─→ ⚠️  Issues → Upload to Security Tab
  │     │
  │     └─→ Test Stack
  │           ├─→ Compose Up
  │           ├─→ Run PHP Version Check
  │           └─→ Compose Down
```

### 4. Security Audit

```
Schedule (Weekly) / Manual
  │
  ├─→ Composer Audit
  │     ├─→ Check Dependencies
  │     ├─→ ✅ No Issues
  │     └─→ ⚠️  Vulnerabilities → Create Issue
  │
  ├─→ CodeQL Analysis
  │     ├─→ Scan PHP Code
  │     ├─→ Scan JavaScript Code
  │     ├─→ ✅ No Issues
  │     └─→ ⚠️  Issues → Security Tab
  │
  └─→ Dependency Review (PRs only)
        ├─→ Compare Dependencies
        ├─→ ✅ Safe Changes
        └─→ ❌ Risky Changes → Block PR
```

### 5. Database Backup

```
Schedule (Daily 2 AM) / Manual
  │
  ├─→ Connect to Server
  │     │
  │     ├─→ Mysqldump via Docker
  │     │     │
  │     │     ├─→ Compress (gzip)
  │     │     │     │
  │     │     │     ├─→ Upload to GitHub Artifacts
  │     │     │     │     └─→ Retention: 30 days
  │     │     │     │
  │     │     │     └─→ Upload to S3 (optional)
  │     │     │           └─→ Storage Class: Standard-IA
  │     │     │
  │     │     └─→ ✅ Success → Cleanup temp files
```

---

## 🏭 Infrastructure

```
┌─────────────────────────────────────────────────────────────┐
│                    GitHub Repository                         │
│                  github.com/MuriloM676/leadflow              │
├─────────────────────────────────────────────────────────────┤
│  ┌───────────────┐  ┌──────────────┐  ┌─────────────────┐  │
│  │  Source Code  │  │  GitHub      │  │  GitHub         │  │
│  │  (main)       │  │  Actions     │  │  Container      │  │
│  │               │  │  Workflows   │  │  Registry       │  │
│  └───────────────┘  └──────────────┘  └─────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│                  Production Server                           │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Docker Compose Stack                                 │  │
│  ├──────────────────────────────────────────────────────┤  │
│  │                                                        │  │
│  │  ┌──────────┐  ┌──────────┐  ┌────────┐  ┌────────┐ │  │
│  │  │  Nginx   │←→│ PHP-FPM  │←→│ MySQL  │  │ Redis  │ │  │
│  │  │  :80     │  │ app      │  │ :3306  │  │ :6379  │ │  │
│  │  └──────────┘  └──────────┘  └────────┘  └────────┘ │  │
│  │                                                        │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Volumes                                              │  │
│  ├──────────────────────────────────────────────────────┤  │
│  │  • mysql_data (persistent)                            │  │
│  │  • /var/www/leadflow (bind mount)                     │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│                    External Services                         │
├─────────────────────────────────────────────────────────────┤
│  • AWS S3 (Backups)                                          │
│  • Slack (Notifications)                                     │
│  • Codecov (Coverage Reports)                                │
│  • GitHub Security (Vulnerability Alerts)                    │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔐 Security Layers

```
┌─────────────────────────────────────────────────────────────┐
│  Layer 1: Code Analysis                                      │
├─────────────────────────────────────────────────────────────┤
│  • PHPStan (Static Analysis)                                 │
│  • Laravel Pint (Code Style)                                 │
│  • CodeQL (Security Patterns)                                │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│  Layer 2: Dependency Scanning                                │
├─────────────────────────────────────────────────────────────┤
│  • Composer Audit (PHP Packages)                             │
│  • Dependency Review (PR Changes)                            │
│  • NPM Audit (Node Packages)                                 │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│  Layer 3: Container Scanning                                 │
├─────────────────────────────────────────────────────────────┤
│  • Trivy (Docker Image Vulnerabilities)                      │
│  • SARIF Upload (GitHub Security Tab)                        │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│  Layer 4: Runtime Protection                                 │
├─────────────────────────────────────────────────────────────┤
│  • Environment Secrets (Encrypted)                           │
│  • Required Reviewers (Production)                           │
│  • SSH Key Authentication                                    │
│  • Daily Backups                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 Monitoring & Observability

```
┌─────────────────────────────────────────────────────────────┐
│  GitHub Actions Dashboard                                    │
├─────────────────────────────────────────────────────────────┤
│  • Workflow Status (Pass/Fail)                               │
│  • Build Times                                               │
│  • Test Coverage Trends                                      │
│  • Security Alerts                                           │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│  GitHub Security Tab                                         │
├─────────────────────────────────────────────────────────────┤
│  • Dependabot Alerts                                         │
│  • CodeQL Findings                                           │
│  • Container Vulnerabilities                                 │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│  Slack Notifications                                         │
├─────────────────────────────────────────────────────────────┤
│  • Deploy Success/Failure                                    │
│  • Commit Info                                               │
│  • Author                                                    │
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 Deployment Strategies

### Blue-Green Deployment (Future)
```
┌───────────┐     ┌───────────┐
│   Blue    │     │   Green   │
│ (Current) │     │   (New)   │
└─────┬─────┘     └─────┬─────┘
      │                 │
      └────────┬────────┘
            Load
          Balancer
```

### Rolling Updates (Current)
```
Old Version → Drain → Stop → New Version → Start → Health Check
```

---

## 💡 Best Practices Implemented

✅ **Parallel Testing** - Run tests faster across multiple PHP versions
✅ **Caching** - Composer dependencies cached between runs
✅ **Multi-stage Builds** - Optimized Docker images
✅ **Security Scanning** - Multiple layers of security checks
✅ **Automated Backups** - Daily database backups with retention
✅ **Health Checks** - Post-deployment verification
✅ **Notifications** - Slack integration for deploy status
✅ **Environment Protection** - Required approvals for production
✅ **Rollback Strategy** - Manual rollback capability
✅ **Documentation** - Comprehensive guides and troubleshooting

---

**Architecture Version:** 1.0.0  
**Last Updated:** 2025-10-25  
**Maintained by:** LeadFlow Team
