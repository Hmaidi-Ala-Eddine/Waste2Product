# 🐳 Docker Setup for Waste2Product Laravel Application

This guide will help you run the Waste2Product application using Docker and Docker Desktop.

## 📋 Prerequisites

1. **Docker Desktop** installed on your machine
   - Windows: [Download Docker Desktop for Windows](https://www.docker.com/products/docker-desktop)
   - Mac: [Download Docker Desktop for Mac](https://www.docker.com/products/docker-desktop)
   - Linux: [Install Docker Engine](https://docs.docker.com/engine/install/)

2. **Git** (to clone the repository if needed)

3. **Basic understanding of Docker** (optional but helpful)

## 🏗️ Architecture

The Docker setup includes the following services:

- **app** - Laravel application with PHP 8.2-FPM + Nginx
- **mysql** - MySQL 8.0 database
- **redis** - Redis 7 for caching and sessions
- **phpmyadmin** - Web-based MySQL management tool
- **queue** - Laravel queue worker for background jobs

## 🚀 Quick Start

### Step 1: Prepare Environment File

Copy the Docker environment file:

```bash
cp .env.docker .env
```

Or manually update your `.env` file with these Docker-specific settings:

```env
DB_HOST=mysql
DB_PASSWORD=secret
REDIS_HOST=redis
SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

### Step 2: Build and Start Containers

Open your terminal in the project directory and run:

```bash
docker-compose up -d --build
```

This command will:
- Build the Docker image
- Start all containers in the background
- Set up the network and volumes

**First-time build may take 5-10 minutes.**

### Step 3: Install Dependencies (if needed)

If you haven't run composer install locally:

```bash
docker-compose exec app composer install
```

### Step 4: Run Migrations

Create database tables:

```bash
docker-compose exec app php artisan migrate
```

Optional: Seed the database with sample data:

```bash
docker-compose exec app php artisan db:seed
```

### Step 5: Generate Application Key (if needed)

```bash
docker-compose exec app php artisan key:generate
```

### Step 6: Create Storage Link

```bash
docker-compose exec app php artisan storage:link
```

### Step 7: Access the Application

- **Main Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Server: `mysql`
  - Username: `root`
  - Password: `secret`

## 🛠️ Common Commands

### View Running Containers

```bash
docker-compose ps
```

### View Logs

View all logs:
```bash
docker-compose logs -f
```

View specific service logs:
```bash
docker-compose logs -f app
docker-compose logs -f mysql
docker-compose logs -f queue
```

### Stop Containers

```bash
docker-compose stop
```

### Start Containers

```bash
docker-compose start
```

### Restart Containers

```bash
docker-compose restart
```

### Stop and Remove Containers

```bash
docker-compose down
```

Remove containers and volumes (⚠️ deletes database):
```bash
docker-compose down -v
```

### Access Container Shell

Enter the app container:
```bash
docker-compose exec app sh
```

Enter the MySQL container:
```bash
docker-compose exec mysql bash
```

### Run Artisan Commands

```bash
docker-compose exec app php artisan [command]
```

Examples:
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:list
docker-compose exec app php artisan queue:work
```

### Run Composer Commands

```bash
docker-compose exec app composer [command]
```

Examples:
```bash
docker-compose exec app composer install
docker-compose exec app composer update
docker-compose exec app composer dump-autoload
```

### Run NPM Commands

```bash
docker-compose exec app npm [command]
```

Examples:
```bash
docker-compose exec app npm install
docker-compose exec app npm run dev
docker-compose exec app npm run build
```

## 🗄️ Database Management

### MySQL Connection Details

When connecting from your host machine:
- Host: `localhost`
- Port: `3307`
- Database: `waste2`
- Username: `root`
- Password: `secret`

When connecting from within Docker containers:
- Host: `mysql`
- Port: `3306`
- Database: `waste2`
- Username: `root`
- Password: `secret`

### Backup Database

```bash
docker-compose exec mysql mysqldump -u root -psecret waste2 > backup.sql
```

### Restore Database

```bash
docker-compose exec -T mysql mysql -u root -psecret waste2 < backup.sql
```

### Access MySQL CLI

```bash
docker-compose exec mysql mysql -u root -psecret waste2
```

## 📦 Volumes

The setup uses Docker volumes for persistent data:

- **mysql_data** - MySQL database files
- **redis_data** - Redis data files

These volumes persist even when containers are removed (unless you use `docker-compose down -v`).

## 🔧 Troubleshooting

### Port Already in Use

If you get an error about ports being in use, you can change them in `docker-compose.yml`:

```yaml
ports:
  - "8001:80"  # Change 8000 to 8001
```

### Permission Issues

If you encounter permission errors:

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Clear All Caches

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Rebuild Containers

If something is not working, try rebuilding:

```bash
docker-compose down
docker-compose up -d --build --force-recreate
```

### View Container Resource Usage

```bash
docker stats
```

### MySQL Connection Refused

Wait a few seconds for MySQL to fully start:

```bash
docker-compose logs -f mysql
```

Look for: `[Server] /usr/sbin/mysqld: ready for connections`

## 🔒 Security Notes

### For Production Deployment:

1. **Change default passwords** in `docker-compose.yml`:
   ```yaml
   MYSQL_ROOT_PASSWORD: your-strong-password
   DB_PASSWORD: your-strong-password
   ```

2. **Update `.env` file**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   DB_PASSWORD=your-strong-password
   ```

3. **Remove phpMyAdmin** service from `docker-compose.yml`

4. **Use HTTPS** with a reverse proxy (nginx, Traefik, or Caddy)

5. **Set proper file permissions**

6. **Enable OPcache** optimization

## 📊 Performance Optimization

### Production Settings

In `docker-compose.yml`, update app service:

```yaml
environment:
  - APP_ENV=production
  - APP_DEBUG=false
```

### Cache Configuration

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## 🔄 Updating the Application

1. Pull latest changes:
   ```bash
   git pull origin main
   ```

2. Rebuild containers:
   ```bash
   docker-compose up -d --build
   ```

3. Run migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

4. Clear caches:
   ```bash
   docker-compose exec app php artisan optimize
   ```

## 📝 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Docker Best Practices](https://laravel.com/docs/deployment)

## 🆘 Getting Help

If you encounter any issues:

1. Check the logs: `docker-compose logs -f`
2. Verify all containers are running: `docker-compose ps`
3. Ensure Docker Desktop is running
4. Try rebuilding: `docker-compose up -d --build --force-recreate`

## 📞 Support

For project-specific issues, please contact the development team or open an issue in the repository.

---

**Happy Coding! 🚀**
