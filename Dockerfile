FROM liushoukun/php-nginx-base:8.2-bate

ARG APP_CODE_PATH=./

# copy php config files
COPY docker/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php-fpm/laravel.ini /usr/local/etc/php/conf.d
COPY docker/php-fpm/xlaravel.pool.conf /usr/local/etc/php-fpm.d/

# copy nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/sites/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/ssl /etc/nginx/ssl

WORKDIR /var/www/app

#RUN composer config -g repo.packagist composer https://mirrors.tencent.com/composer/
# install application dependencies
COPY ${APP_CODE_PATH} .
RUN composer install --no-scripts --no-autoloader --ansi --no-interaction --no-dev -vvv
# copy application code
RUN composer dump-autoload -o
RUN chown -R :www-data /var/www/app && chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache


###########################################################################
# Supervisord
###########################################################################

COPY docker/supervisord/supervisord.conf /etc/supervisord.conf
COPY docker/supervisord/conf/*  /etc/supervisord/


###########################################################################
# Crontab
###########################################################################

COPY ./docker/crontab /etc/crontabs
RUN chmod -R 644 /etc/crontabs



ADD ./docker/startup.sh /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh

EXPOSE 80 443

# run start
CMD ["/bin/sh","/opt/startup.sh"]
