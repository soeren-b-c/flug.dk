FROM php:7.2-apache

COPY httpd/conf.d/flug.dk.conf /etc/apache2/sites-available/
COPY . /var/www/html/

RUN a2enmod rewrite
RUN a2ensite flug.dk

RUN ln -sf /dev/stdout /var/log/apache2/access_log && ln -sf /dev/stderr /var/log/apache2/error_log
