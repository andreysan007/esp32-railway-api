# Base image PHP + Apache
FROM php:8.2-apache

# Copy semua file project ke direktori web server
COPY . /var/www/html/

# Set permission yang sesuai (opsional)
RUN chown -R www-data:www-data /var/www/html

# Enable mod_rewrite bila diperlukan
RUN a2enmod rewrite

# Railway otomatis expose port 8080, jadi kita expose juga port 8080 di container
EXPOSE 8080

# Jalankan Apache di foreground, tapi dengan port 8080
CMD ["apache2-foreground"]
