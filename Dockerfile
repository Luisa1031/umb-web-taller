# Usar una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Habilitar extensiones de PHP necesarias (MySQL/PDO)
# La imagen php-apache ya incluye pdo_mysql, pero es buena práctica verificar
RUN docker-php-ext-install pdo_mysql

# Copiar todos los archivos de la API al directorio web del servidor Apache
COPY api/ /var/www/html/

# Habilitar mod_rewrite para URLs amigables (aunque no es estrictamente necesario 
# para este ejemplo con index.php, es una buena práctica para APIs)
RUN a2enmod rewrite

# El puerto 80 es el puerto por defecto de Apache, Render lo mapeará al exterior.
EXPOSE 80