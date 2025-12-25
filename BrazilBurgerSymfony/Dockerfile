# Étape 1 : Base PHP avec extensions nécessaires
FROM php:8.4-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    zlib1g-dev \
    libpq-dev \
    curl \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 2 : Définir le répertoire de travail
WORKDIR /app

# Copier uniquement le projet Symfony
COPY BrazilBurgerSymfony/ ./

# Installer les dépendances PHP
ENV APP_ENV=prod
ENV APP_DEBUG=0

# 3. On lance l'installation (c'est ici que ça plante chez toi)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# 4. On lance les scripts APRES l'installation si nécessaire
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative
# Exposer le port 8000
EXPOSE 8000

# Commande pour lancer Symfony en prod
CMD ["php", "-d", "memory_limit=-1", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]

