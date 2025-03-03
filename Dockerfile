# Usa una imagen de PHP con extensiones necesarias
FROM php:8.2-cli

# Instala extensiones necesarias para MySQL y Composer
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias
RUN composer install

# Expone el puerto de Slim (opcional, si usas un servidor integrado)
EXPOSE 8000

# Comando para ejecutar las migraciones al iniciar el contenedor
CMD php vendor/bin/phinx migrate && php vendor/bin/phinx seed:run && php -S 0.0.0.0:8000 -t public
