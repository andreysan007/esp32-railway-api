# Gunakan image PHP bawaan dengan built-in web server
FROM php:8.2-cli

# Copy semua file ke dalam container
COPY . /usr/src/myapp

# Ganti direktori kerja ke dalam folder tersebut
WORKDIR /usr/src/myapp

# Expose port 8080 untuk Railway
EXPOSE 8080

# Jalankan built-in PHP server
CMD ["php", "-S", "0.0.0.0:8080", "index.php"]