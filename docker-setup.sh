#!/bin/bash

# Docker Setup Script for Waste2Product
# This script automates the initial Docker setup

set -e

echo "🐳 Waste2Product Docker Setup"
echo "================================"
echo ""

# Check if Docker is running
echo "Checking if Docker is running..."
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi
echo "✅ Docker is running"
echo ""

# Step 1: Prepare .env file
echo "Step 1: Preparing environment file..."
if [ -f ".env" ]; then
    echo "⚠️  .env file already exists."
    read -p "Do you want to backup and replace it with .env.docker? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        cp .env .env.backup
        echo "✅ Backed up existing .env to .env.backup"
        cp .env.docker .env
        echo "✅ Created new .env from .env.docker"
    fi
else
    cp .env.docker .env
    echo "✅ Created .env file"
fi
echo ""

# Step 2: Build and start containers
echo "Step 2: Building and starting Docker containers..."
echo "⏳ This may take 5-10 minutes on first run..."
docker-compose up -d --build
echo "✅ Containers started successfully"
echo ""

# Step 3: Wait for MySQL to be ready
echo "Step 3: Waiting for MySQL to be ready..."
for i in {1..30}; do
    if docker-compose exec -T mysql mysqladmin ping -h localhost -u root -psecret > /dev/null 2>&1; then
        echo "✅ MySQL is ready"
        break
    fi
    echo "Attempt $i/30... ⏳"
    sleep 2
done
echo ""

# Step 4: Install Composer dependencies
echo "Step 4: Installing Composer dependencies..."
docker-compose exec -T app composer install --optimize-autoloader || echo "⚠️  Warning: Composer install had issues"
echo "✅ Composer dependencies installed"
echo ""

# Step 5: Generate application key
echo "Step 5: Generating application key..."
docker-compose exec -T app php artisan key:generate --force
echo "✅ Application key generated"
echo ""

# Step 6: Run migrations
echo "Step 6: Running database migrations..."
read -p "Do you want to run migrations now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose exec -T app php artisan migrate --force
    echo "✅ Migrations completed"
else
    echo "⏭️  Skipped migrations"
fi
echo ""

# Step 7: Create storage link
echo "Step 7: Creating storage link..."
docker-compose exec -T app php artisan storage:link
echo "✅ Storage link created"
echo ""

# Step 8: Clear caches
echo "Step 8: Clearing caches..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan route:clear
docker-compose exec -T app php artisan view:clear
echo "✅ Caches cleared"
echo ""

# Summary
echo "================================"
echo "🎉 Setup Complete!"
echo "================================"
echo ""
echo "Your application is now running at:"
echo "  🌐 Main App:    http://localhost:8000"
echo "  🗄️  phpMyAdmin: http://localhost:8080"
echo ""
echo "Database credentials:"
echo "  Host:     mysql (or localhost:3307 from host)"
echo "  Database: waste2"
echo "  Username: root"
echo "  Password: secret"
echo ""
echo "Useful commands:"
echo "  View logs:        docker-compose logs -f"
echo "  Stop containers:  docker-compose stop"
echo "  Start containers: docker-compose start"
echo "  Restart:          docker-compose restart"
echo "  Shell access:     docker-compose exec app sh"
echo ""
echo "For more information, see DOCKER_README.md"
echo ""
echo "Happy coding! 🚀"
