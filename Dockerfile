# Gunakan image PHP bawaan
FROM php:8.1-apache

# Salin semua file project ke dalam container
COPY . /var/www/html/

# Expose port default Apache
EXPOSE 80
