FROM dunglas/frankenphp:php8.4

# MySQL ke liye PDO extension install
RUN install-php-extensions pdo_mysql

# Project ki tamam files container mein copy
COPY . /app

# Keep the bundled demo uploads in the runtime path used by the PHP app.
RUN mkdir -p /app/uploads \
	&& if [ -d /app/.github/uploads ]; then cp -R /app/.github/uploads/. /app/uploads/; fi

# Project directory
WORKDIR /app

# Web server port
EXPOSE 8080