FROM alpine:3.8

# Installeer alle benodigde packages + freetds-dev voor tsql
RUN apk add --no-cache \
    nginx \
    php5-fpm \
    php5-pdo \
    php5-pdo_dblib \
    php5-mssql \
    php5-json \
    php5-openssl \
    php5-curl \
    php5-gd \
    php5-xml \
    php5-ctype \
    freetds \
    freetds-dev \
    ca-certificates \
    openssl \
    supervisor

# Update de root certificaten in de container
RUN update-ca-certificates

# FreeTDS config: MET [azure] sectie EN MAXIMALE TLS TOLERANTIE
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
validate = no\n' > /etc/freetds.conf

# Nginx config
RUN printf 'server {\n\
    listen 80;\n\
    root /var/www;\n\
    index index.php index.html;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}\n' > /etc/nginx/conf.d/default.conf

# Supervisor config
RUN printf '[supervisord]\n\
nodaemon=true\n\
\n\
[program:php-fpm]\n\
command=php-fpm5 -F\n\
autostart=true\n\
autorestart=true\n\
\n\
[program:nginx]\n\
command=nginx -g "daemon off;"\n\
autostart=true\n\
autorestart=true\n' > /etc/supervisord.conf

RUN mkdir -p /var/www /run/nginx

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
