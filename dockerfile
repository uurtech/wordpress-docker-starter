FROM composer as builder

WORKDIR /app
COPY src/composer.json /app/
RUN composer install  \
    --ignore-platform-reqs \
    --no-interaction \
    --no-scripts

FROM ubuntu:20.04

RUN apt-get update -y && apt-get -y install tzdata
RUN echo "Europe/Amsterdam" > /etc/timezone
RUN dpkg-reconfigure -f noninteractive tzdata

RUN apt-get update -y && apt-get install nginx mysql-client ssmtp -y
RUN apt-get update -y && apt-get install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get install wget curl -y
RUN apt update -y

RUN apt install -y php7.4-common php7.4-mysql php7.4-gmp php7.4-curl php7.4-intl php7.4-mbstring php7.4-xmlrpc php7.4-gd php7.4-xml php7.4-cli php7.4-zip
RUN cd /tmp && wget https://wordpress.org/latest.tar.gz && tar -xvzf latest.tar.gz && mv wordpress/* /var/www/html

RUN apt install -y php7.4-fpm

RUN apt update \
    && curl -sL https://deb.nodesource.com/setup_12.x  | bash - \
    && apt install -y nodejs \
    && npm install -g sass

RUN chmod -R 755 /var/www/html
COPY --from=builder /app/vendor/ /var/www/html/vendor/
COPY --from=builder /app/ /var/www/html/

COPY src/conf/default /etc/nginx/sites-available/default
COPY src/conf/php.ini /etc/php/7.4/fpm/php.ini
RUN chown -R www-data:www-data /etc/nginx/sites-available
COPY src/entrypoint.sh .
COPY src/wp-config.php /var/www/html/wp-config.php
COPY src/load.php /tmp/load.php
COPY src/robots.txt /var/www/html/

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html
ENTRYPOINT ["bash","./entrypoint.sh"]
