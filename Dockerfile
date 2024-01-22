FROM php:8.2-apache

COPY app /var/www/html/app
COPY public /var/www/html/public
COPY system /var/www/html/system
COPY vendor /var/www/html/vendor
COPY writable /var/www/html/writable
COPY env /var/www/html/env
COPY .env /var/www/html/.env
RUN chmod -R 777 /var/www/html/writable

COPY dockers/config/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY dockers/config/ssl/568int.com.pem /opt/ssl/568int.com.pem

## Required libs for the app
RUN apt-get update \
    && apt-get install -y libzip-dev vim nano libicu-dev libonig-dev python3 pip python3-dev default-libmysqlclient-dev build-essential pkg-config systemctl default-mysql-client \
    && docker-php-ext-install zip mysqli \
    && docker-php-ext-install mbstring \
    && docker-php-ext-enable mysqli zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && a2enmod rewrite && a2enmod ssl

RUN ln -s /var/www/html/app/ThirdParty/live_capture.service /etc/systemd/system/livecapture.service \
    && systemctl daemon-reload \
    && systemctl enable livecapture.service

## Crontab
RUN apt-get update \
 && apt-get install -y --no-install-recommends cron rsyslog \
 && rm -rf /etc/cron.*

COPY dockers/config/crontab /tmp/crontab

RUN crontab < /tmp/crontab && rm -f /tmp/crontab

## Python
RUN mv /usr/lib/python3.11/EXTERNALLY-MANAGED /usr/lib/python3.11/EXTERNALLY-MANAGED.old
RUN pip install -U pyzk mysql-connector-python
RUN mv /usr/lib/python3.11/EXTERNALLY-MANAGED.old /usr/lib/python3.11/EXTERNALLY-MANAGED

RUN apt-get update && apt-get install -y net-tools telnet


RUN apt-get clean && rm -rf /tmp/* /var/tmp/* && rm -rf /var/lib/apt/lists/*
