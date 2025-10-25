#!/bin/bash

# GitHub Actions Secrets Setup Helper
# Run this script to configure GitHub repository secrets

set -e

echo "🔐 GitHub Actions Secrets Configuration"
echo "========================================"
echo ""

# Check if gh CLI is installed
if ! command -v gh &> /dev/null; then
    echo "❌ GitHub CLI (gh) not found. Please install it first:"
    echo "   https://cli.github.com/"
    exit 1
fi

# Check if logged in
if ! gh auth status &> /dev/null; then
    echo "❌ Not logged in to GitHub CLI. Please run: gh auth login"
    exit 1
fi

echo "✅ GitHub CLI is ready"
echo ""

# Get repository
REPO=$(gh repo view --json nameWithOwner -q .nameWithOwner)
echo "📦 Repository: $REPO"
echo ""

# Function to set secret
set_secret() {
    local name=$1
    local description=$2
    local required=$3
    
    echo "🔑 $name"
    echo "   $description"
    
    if [ "$required" = "required" ]; then
        echo -n "   Required - Enter value: "
    else
        echo -n "   Optional - Enter value (or press Enter to skip): "
    fi
    
    read -s value
    echo ""
    
    if [ -n "$value" ]; then
        echo "$value" | gh secret set "$name" --repo="$REPO"
        echo "   ✅ Secret set successfully"
    else
        if [ "$required" = "required" ]; then
            echo "   ⚠️  Warning: Required secret not set"
        else
            echo "   ⏭️  Skipped"
        fi
    fi
    echo ""
}

echo "📋 DEPLOYMENT SECRETS"
echo "===================="
echo ""

set_secret "SSH_PRIVATE_KEY" "SSH private key for deployment" "required"
set_secret "SSH_USER" "SSH username (e.g., ubuntu, root)" "required"
set_secret "SERVER_HOST" "Server IP or hostname" "required"
set_secret "DEPLOY_PATH" "Deploy path on server (e.g., /var/www/leadflow)" "required"
set_secret "APP_URL" "Application URL for health checks" "required"

echo ""
echo "📋 OPTIONAL SECRETS"
echo "==================="
echo ""

set_secret "SLACK_WEBHOOK" "Slack webhook URL for notifications" "optional"

echo ""
echo "📋 AWS S3 BACKUP SECRETS (Optional)"
echo "===================================="
echo ""

set_secret "AWS_ACCESS_KEY_ID" "AWS Access Key ID" "optional"
set_secret "AWS_SECRET_ACCESS_KEY" "AWS Secret Access Key" "optional"
set_secret "AWS_REGION" "AWS Region (e.g., us-east-1)" "optional"
set_secret "S3_BACKUP_BUCKET" "S3 Bucket name for backups" "optional"

echo ""
echo "📋 CODECOV (Optional)"
echo "====================="
echo ""

set_secret "CODECOV_TOKEN" "Codecov token (only for private repos)" "optional"

echo ""
echo "✅ Configuration complete!"
echo ""
echo "📝 Next steps:"
echo "   1. Verify secrets at: https://github.com/$REPO/settings/secrets/actions"
echo "   2. Configure production environment at: https://github.com/$REPO/settings/environments"
echo "   3. Test workflows by pushing to main branch"
echo ""
echo "📚 For more info, see: .github/WORKFLOWS.md"
echo ""
