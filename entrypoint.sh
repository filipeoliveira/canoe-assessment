#!/bin/sh

# Run migrations
php artisan migrate --seed

# Start the Laravel development server
php artisan serve --host=0.0.0.0 --port=8000
