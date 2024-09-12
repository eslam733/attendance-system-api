#!/bin/bash

# Ensure the correct ownership of the files
chown -R sprints:sprints /var/www/html

# Check if the vendor directory exists
if [ ! -d "/var/www/html/vendor" ]; then
  echo "Vendor directory not found, running 'composer install'..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Check if node_modules directory exists
#if [ ! -d "/var/www/html/node_modules" ]; then
#  echo "Node modules not found, installing npm dependencies..."
#  npm install
#fi
#
#echo "Building assets..."
#npm run prod

# Run the main container command
exec "$@"
