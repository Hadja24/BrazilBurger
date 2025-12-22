# PHP 8.4 CLI
FROM php:8.4-cli

# Installer dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    zlib1g-dev \
    curl \
    libpq-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /app

# Copier tout le projet dans le conteneur
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 8000 pour Render
EXPOSE 8000

# Commande pour lancer Symfony
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
