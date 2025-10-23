# Docker Setup Script for Waste2Product
# This script automates the initial Docker setup

Write-Host "🐳 Waste2Product Docker Setup" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Check if Docker is running
Write-Host "Checking if Docker is running..." -ForegroundColor Yellow
$dockerRunning = docker info 2>&1 | Select-String "Server"
if (-not $dockerRunning) {
    Write-Host "❌ Docker is not running. Please start Docker Desktop first." -ForegroundColor Red
    exit 1
}
Write-Host "✅ Docker is running" -ForegroundColor Green
Write-Host ""

# Step 1: Prepare .env file
Write-Host "Step 1: Preparing environment file..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "⚠️  .env file already exists." -ForegroundColor Yellow
    $response = Read-Host "Do you want to backup and replace it with .env.docker? (y/n)"
    if ($response -eq "y") {
        Copy-Item ".env" ".env.backup" -Force
        Write-Host "✅ Backed up existing .env to .env.backup" -ForegroundColor Green
        Copy-Item ".env.docker" ".env" -Force
        Write-Host "✅ Created new .env from .env.docker" -ForegroundColor Green
    }
} else {
    Copy-Item ".env.docker" ".env" -Force
    Write-Host "✅ Created .env file" -ForegroundColor Green
}
Write-Host ""

# Step 2: Build and start containers
Write-Host "Step 2: Building and starting Docker containers..." -ForegroundColor Yellow
Write-Host "⏳ This may take 5-10 minutes on first run..." -ForegroundColor Yellow
docker-compose up -d --build
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Containers started successfully" -ForegroundColor Green
} else {
    Write-Host "❌ Failed to start containers" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Step 3: Wait for MySQL to be ready
Write-Host "Step 3: Waiting for MySQL to be ready..." -ForegroundColor Yellow
$maxAttempts = 30
$attempt = 0
$mysqlReady = $false

while (-not $mysqlReady -and $attempt -lt $maxAttempts) {
    $attempt++
    Write-Host "Attempt $attempt/$maxAttempts..." -NoNewline
    $result = docker-compose exec -T mysql mysqladmin ping -h localhost -u root -psecret 2>&1
    if ($result -match "mysqld is alive") {
        $mysqlReady = $true
        Write-Host " ✅" -ForegroundColor Green
    } else {
        Write-Host " ⏳" -ForegroundColor Yellow
        Start-Sleep -Seconds 2
    }
}

if (-not $mysqlReady) {
    Write-Host "❌ MySQL failed to start" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Step 4: Install Composer dependencies
Write-Host "Step 4: Installing Composer dependencies..." -ForegroundColor Yellow
docker-compose exec -T app composer install --optimize-autoloader
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Composer dependencies installed" -ForegroundColor Green
} else {
    Write-Host "⚠️  Warning: Composer install had issues (may already be installed)" -ForegroundColor Yellow
}
Write-Host ""

# Step 5: Generate application key
Write-Host "Step 5: Generating application key..." -ForegroundColor Yellow
docker-compose exec -T app php artisan key:generate --force
Write-Host "✅ Application key generated" -ForegroundColor Green
Write-Host ""

# Step 6: Run migrations
Write-Host "Step 6: Running database migrations..." -ForegroundColor Yellow
$response = Read-Host "Do you want to run migrations now? (y/n)"
if ($response -eq "y") {
    docker-compose exec -T app php artisan migrate --force
    Write-Host "✅ Migrations completed" -ForegroundColor Green
} else {
    Write-Host "⏭️  Skipped migrations" -ForegroundColor Yellow
}
Write-Host ""

# Step 7: Create storage link
Write-Host "Step 7: Creating storage link..." -ForegroundColor Yellow
docker-compose exec -T app php artisan storage:link
Write-Host "✅ Storage link created" -ForegroundColor Green
Write-Host ""

# Step 8: Clear caches
Write-Host "Step 8: Clearing caches..." -ForegroundColor Yellow
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan route:clear
docker-compose exec -T app php artisan view:clear
Write-Host "✅ Caches cleared" -ForegroundColor Green
Write-Host ""

# Summary
Write-Host "================================" -ForegroundColor Cyan
Write-Host "🎉 Setup Complete!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Your application is now running at:" -ForegroundColor White
Write-Host "  🌐 Main App:    http://localhost:8000" -ForegroundColor Cyan
Write-Host "  🗄️  phpMyAdmin: http://localhost:8080" -ForegroundColor Cyan
Write-Host ""
Write-Host "Database credentials:" -ForegroundColor White
Write-Host "  Host:     mysql (or localhost:3307 from host)" -ForegroundColor Gray
Write-Host "  Database: waste2" -ForegroundColor Gray
Write-Host "  Username: root" -ForegroundColor Gray
Write-Host "  Password: secret" -ForegroundColor Gray
Write-Host ""
Write-Host "Useful commands:" -ForegroundColor White
Write-Host "  View logs:        docker-compose logs -f" -ForegroundColor Gray
Write-Host "  Stop containers:  docker-compose stop" -ForegroundColor Gray
Write-Host "  Start containers: docker-compose start" -ForegroundColor Gray
Write-Host "  Restart:          docker-compose restart" -ForegroundColor Gray
Write-Host "  Shell access:     docker-compose exec app sh" -ForegroundColor Gray
Write-Host ""
Write-Host "For more information, see DOCKER_README.md" -ForegroundColor Yellow
Write-Host ""
Write-Host "Happy coding! 🚀" -ForegroundColor Green
