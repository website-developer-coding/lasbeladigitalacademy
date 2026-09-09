FROM dunglas/frankenphp:php8.4

COPY Caddyfile /etc/frankenphp/Caddyfile

# MySQL ke liye PDO extension install
RUN install-php-extensions pdo_mysql

# Project ki tamam files container mein copy
COPY . /app

# Ensure upload directories exist when a persistent Railway Volume is mounted.
RUN mkdir -p /app/uploads/courses /app/uploads/gallery /app/uploads/services

# Project directory
WORKDIR /app

# Web server port
EXPOSE 8080