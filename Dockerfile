FROM php:8.1-apache
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y git
RUN apt-get install -y cron
RUN apt-get install -y tzdata
RUN cp /usr/share/zoneinfo/Pacific/Auckland /etc/localtime && \
    echo "Etc/Zulu" > /etc/timezone
COPY ./cronjobs/cron /etc/cron.d/cron
RUN chmod 0644 /etc/cron.d/cron
RUN service cron start
RUN crontab /etc/cron.d/cron
RUN sed -i 's/^exec /service cron start\nprintenv > \/etc\/environment\n\nexec /' /usr/local/bin/apache2-foreground
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli
RUN docker-php-ext-install pdo 
RUN docker-php-ext-install pdo_mysql
ADD app/ /var/www/html/
RUN a2enmod rewrite
COPY --from=composer:lts /usr/bin/composer /usr/local/bin/composer
#switch to /var/www/html
WORKDIR /var/www/html
#run composer update and install
RUN composer update
RUN composer install
EXPOSE 80
