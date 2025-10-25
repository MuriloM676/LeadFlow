@echo off
echo ========================================
echo   CRM Vendas - Setup Automatico
echo ========================================
echo.

REM Verificar se Composer esta instalado
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERRO] Composer nao encontrado!
    echo Por favor, instale o Composer de: https://getcomposer.org/
    pause
    exit /b 1
)

echo [1/10] Instalando dependencias do Laravel...
call composer install
if %ERRORLEVEL% NEQ 0 (
    echo [ERRO] Falha ao instalar dependencias
    pause
    exit /b 1
)

echo.
echo [2/10] Instalando Filament...
call composer require filament/filament:"^3.0-stable" -W
if %ERRORLEVEL% NEQ 0 (
    echo [AVISO] Filament pode ja estar instalado
)

echo.
echo [3/10] Configurando Filament Panel...
call php artisan filament:install --panels
if %ERRORLEVEL% NEQ 0 (
    echo [AVISO] Filament Panel pode ja estar configurado
)

echo.
echo [4/10] Criando arquivo .env...
if not exist .env (
    copy .env.example .env
    echo Arquivo .env criado!
) else (
    echo Arquivo .env ja existe.
)

echo.
echo [5/10] Gerando chave da aplicacao...
call php artisan key:generate

echo.
echo [6/10] Iniciando banco de dados MySQL...
call docker-compose up -d
if %ERRORLEVEL% NEQ 0 (
    echo [ERRO] Falha ao iniciar Docker
    echo Certifique-se de que o Docker Desktop esta rodando
    pause
    exit /b 1
)

echo.
echo [7/10] Aguardando MySQL inicializar (15 segundos)...
timeout /t 15 /nobreak

echo.
echo [8/10] Executando migrations...
call php artisan migrate --force
if %ERRORLEVEL% NEQ 0 (
    echo [ERRO] Falha ao executar migrations
    echo Verifique se o MySQL esta rodando corretamente
    pause
    exit /b 1
)

echo.
echo [9/10] Populando banco de dados com dados de teste...
call php artisan db:seed
if %ERRORLEVEL% NEQ 0 (
    echo [AVISO] Falha ao popular banco
)

echo.
echo [10/10] Instalando dependencias Node.js...
where npm >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    call npm install
    echo Dependencias Node.js instaladas!
) else (
    echo [AVISO] NPM nao encontrado. Instale Node.js de: https://nodejs.org/
)

echo.
echo ========================================
echo   Setup concluido com sucesso!
echo ========================================
echo.
echo Credenciais de acesso:
echo   URL: http://localhost:8000/admin
echo   Gestor: gestor@example.com / password
echo   Vendedor: vendedor@example.com / password
echo.
echo Para iniciar o servidor:
echo   php artisan serve
echo.
echo Para compilar assets (em outro terminal):
echo   npm run dev
echo.
pause
