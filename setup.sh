#!/bin/bash

echo "🚀 Setting up Blog Application..."

# Build and start containers
echo "📦 Building Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Install backend dependencies
echo "📥 Installing backend dependencies..."
docker-compose exec backend composer install

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec backend php artisan key:generate

# Run migrations
echo "🗄️  Running database migrations..."
docker-compose exec backend php artisan migrate --force

# Create storage link
echo "🔗 Creating storage link..."
docker-compose exec backend php artisan storage:link

# Set permissions
echo "🔐 Setting permissions..."
docker-compose exec backend chown -R www-data:www-data /var/www/html/storage
docker-compose exec backend chmod -R 755 /var/www/html/storage

echo ""
echo "✅ Setup complete!"
echo ""
echo "🌐 Access your application:"
echo "   - Frontend: http://localhost:3000"
echo "   - Backend API: http://localhost:8000"
echo "   - MailHog UI: http://localhost:8025"
echo ""
echo "📝 Useful commands:"
echo "   - Stop containers: docker-compose down"
echo "   - View logs: docker-compose logs -f"
echo "   - Backend shell: docker-compose exec backend bash"
echo "   - Frontend shell: docker-compose exec frontend sh"
echo ""
