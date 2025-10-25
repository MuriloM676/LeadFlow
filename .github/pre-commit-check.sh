#!/bin/bash

# Pre-commit Quality Check Script
# Run this before committing to ensure code quality

set -e

echo "🔍 Running pre-commit quality checks..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if composer is available
if ! command -v composer &> /dev/null; then
    echo -e "${RED}❌ Composer not found${NC}"
    exit 1
fi

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo -e "${YELLOW}⚠️  Installing dependencies...${NC}"
    composer install --quiet
fi

# 1. Laravel Pint (Code Style)
echo "📝 Checking code style (Laravel Pint)..."
if ./vendor/bin/pint --test --quiet; then
    echo -e "${GREEN}✅ Code style check passed${NC}"
else
    echo -e "${RED}❌ Code style issues found${NC}"
    echo -e "${YELLOW}💡 Run './vendor/bin/pint' to fix automatically${NC}"
    exit 1
fi
echo ""

# 2. PHPStan (Static Analysis)
echo "🔬 Running static analysis (PHPStan)..."
if ./vendor/bin/phpstan analyse --memory-limit=2G --no-progress --error-format=table; then
    echo -e "${GREEN}✅ Static analysis passed${NC}"
else
    echo -e "${RED}❌ Static analysis found issues${NC}"
    echo -e "${YELLOW}💡 Fix the issues above before committing${NC}"
    exit 1
fi
echo ""

# 3. PHPUnit Tests
echo "🧪 Running tests..."
if php artisan test --parallel --quiet; then
    echo -e "${GREEN}✅ All tests passed${NC}"
else
    echo -e "${RED}❌ Some tests failed${NC}"
    echo -e "${YELLOW}💡 Fix failing tests before committing${NC}"
    exit 1
fi
echo ""

# 4. Security Audit
echo "🔒 Checking for security vulnerabilities..."
if composer audit --no-dev --quiet; then
    echo -e "${GREEN}✅ No security vulnerabilities found${NC}"
else
    echo -e "${YELLOW}⚠️  Security vulnerabilities detected${NC}"
    echo -e "${YELLOW}💡 Review and update vulnerable packages${NC}"
fi
echo ""

# 5. Check for debug statements
echo "🔍 Checking for debug statements..."
if git diff --cached --name-only | grep -E '\.(php)$' | xargs grep -E '(dd\(|dump\(|var_dump\(|print_r\(|console\.log\()' > /dev/null 2>&1; then
    echo -e "${YELLOW}⚠️  Debug statements found:${NC}"
    git diff --cached --name-only | grep -E '\.(php)$' | xargs grep -nE '(dd\(|dump\(|var_dump\(|print_r\(|console\.log\()'
    echo ""
    echo -e "${YELLOW}💡 Remove debug statements before committing${NC}"
    read -p "Continue anyway? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
else
    echo -e "${GREEN}✅ No debug statements found${NC}"
fi
echo ""

# Success!
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}✅ All quality checks passed!${NC}"
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo "🚀 Ready to commit!"
