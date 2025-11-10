# FASE 1: De PHP Bouw-omgeving
# We gebruiken de officiële PHP 5.6-FPM image, die gebaseerd is op Debian (glibc).
FROM php:5.6-fpm as php_stage

# De Debian-basis is oud, dus we moeten de sources aanpassen naar het archief.
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i 's|security.debian.org|archive.debian.org/debian-security|g' /etc/apt/sources.list && \
    sed -i '/jessie-updates/d' /etc/apt/sources.list

# **DE FIX:** Vertel APT om de verlopen GPG sleutel van het archief te vertrouwen.
RUN apt-get update -o Acquire::Check-Valid-Until=false && apt-get install -y --no-install-recommends \
    freetds-dev \
    libsybdb5 \
    && docker-php-ext-install pdo_dblib curl gd xml

# FASE 2: De Definitieve Productie-image
# We starten met een schone, moderne en veilige basis (glibc).
FROM ubuntu:20.04

# Voorkom interactieve vragen tijdens installatie
ENV DEBIAN_FRONTEND=noninteractive

# Installeer alleen de software die we echt nodig hebben: Nginx, Supervisor en de FreeTDS-bibliotheken.
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    freetds-bin \
    ca-certificates && \
    rm -rf /var/lib/apt/lists/*

# --- Kopieer de Magie ---
# Kopieer de compatibele, kant-en-klare PHP 5.6 bestanden uit de bouw-omgeving.
COPY --from=php_stage /usr/local/bin/php* /usr/local/bin/
COPY --from=php_stage /usr/local/sbin/php-fpm* /usr/local/sbin/
COPY --from=php_stage /usr/local/etc/ /usr/local/etc/
COPY --from=php_stage /usr/lib/php/ /usr/lib/php/

# Zorg ervoor dat de PHP-FPM configuratie correct is voor de nieuwe omgeving.
RUN sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php\/php5.6-fpm.sock/g' /usr/local/etc/php-fpm.d/www.conf

# Configureer FreeTDS
RUN printf '[global]\n\
    tds version = 7.4\n\
    encryption = require\n\
    text size = 64512\n\
    \n\
    [azure]\n\
    host = iqmatrix.database.windows.net\n\
    port = 1433\n\
    tds version = 7.4\n\
    encryption = require\n\
    check certificate hostname = no\n\
    validate = no\n' > /etc/freetds/freetds.conf

# Configureer Nginx om met de PHP-FPM socket te praten
RUN printf 'server {\n\
    listen 80 default_server;\n\
    root /var/www;\n\
    index index.php index.html;\n\
    location / {\n\
    try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
    include fastcgi_params;\n\
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
    fastcgi_pass unix:/var/run/php/php5.6-fpm.sock;\n\
    }\n}\n' > /etc/nginx/sites-available/default

# Configureer Supervisor
RUN printf '[supervisord]\n\
    nodaemon=true\n\
    \n\
    [program:php-fpm]\n\
    command=/usr/local/sbin/php-fpm --nodaemonize\n\
    autostart=true\n\
    autorestart=true\n\
    \n\
    [program:nginx]\n\
    command=nginx -g "daemon off;"\n\
    autostart=true\n\
    autorestart=true\n' > /etc/supervisor/conf.d/supervisord.conf

# Maak de benodigde directories aan
RUN mkdir -p /var/www /var/run/php

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]