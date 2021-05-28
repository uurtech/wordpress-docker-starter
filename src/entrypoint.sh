if [ -z ${IS_ON_FARGATE+x} ]; then echo "local" ; else cp /tmp/load.php /var/www/html/wp-includes/load.php; fi

/etc/init.d/php7.4-fpm start

cat /var/www/html/wp-config.php

nginx -g "daemon off;"