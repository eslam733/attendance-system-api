# Use the official PHP image with Apache
FROM php:8.2-apache

# Update system packages and install the necessary packages
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libxslt1-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libssl-dev \
    libc-client-dev \
    libkrb5-dev \
    libreadline-dev \
    gettext-base \
    autoconf \
    g++ \
    make \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP extensions that require special flags
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl

# Install PHP extensions one by one
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) iconv
RUN docker-php-ext-install -j$(nproc) curl
RUN docker-php-ext-install -j$(nproc) xml
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) exif
RUN docker-php-ext-install -j$(nproc) bcmath
RUN docker-php-ext-install -j$(nproc) calendar
RUN docker-php-ext-install -j$(nproc) ctype
RUN docker-php-ext-install -j$(nproc) dom
RUN docker-php-ext-install -j$(nproc) fileinfo
RUN docker-php-ext-install -j$(nproc) filter
RUN docker-php-ext-install -j$(nproc) ftp
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) pcntl
RUN docker-php-ext-install -j$(nproc) phar
RUN docker-php-ext-install -j$(nproc) posix
RUN docker-php-ext-install -j$(nproc) session
RUN docker-php-ext-install -j$(nproc) shmop
RUN docker-php-ext-install -j$(nproc) simplexml
RUN docker-php-ext-install -j$(nproc) sockets
RUN docker-php-ext-install -j$(nproc) sysvmsg
RUN docker-php-ext-install -j$(nproc) sysvsem
RUN docker-php-ext-install -j$(nproc) sysvshm
RUN docker-php-ext-install -j$(nproc) xmlwriter
RUN docker-php-ext-install -j$(nproc) xsl


# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set the DocumentRoot to Laravel's public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Allow override for Laravel's .htaccess
RUN echo "<Directory /var/www/html/public>" >> /etc/apache2/apache2.conf \
    && echo "AllowOverride All" >> /etc/apache2/apache2.conf \
    && echo "Require all granted" >> /etc/apache2/apache2.conf \
    && echo "</Directory>" >> /etc/apache2/apache2.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite headers

# Install Composer if not exists
RUN if [ ! -f /usr/local/bin/composer ]; then \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
fi


# Copy the application's code from the local context to /var/www/html inside the container
COPY . /var/www/html

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /var/www/html user
RUN chown -R user:user /var/www/html

# Set the working directory to the root of Apache
WORKDIR /var/www/html

# Set the correct permissions on the storage and bootstrap/cache directories
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy the entrypoint script into the container
COPY entrypoint.sh /usr/local/bin/

# Set the script to be executable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Switch to sprints
USER user

# Expose port 80 to access Apache
EXPOSE 80

# Use the script as the entrypoint
ENTRYPOINT ["entrypoint.sh"]

# When the container starts, start Apache in the foreground
CMD ["apache2-foreground"]
