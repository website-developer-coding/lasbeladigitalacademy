FROM dunglas/frankenphp:php8.4

# MySQL ke liye PDO extension install
RUN install-php-extensions pdo_mysql

# Project ki tamam files container mein copy
COPY . /app

# Project directory
WORKDIR /app

# Web server port
EXPOSE 8080