FROM php:7.3.2-apache-stretch

RUN ln -s /usr/local/bin/php /usr/bin/php
RUN ln -s /usr/bin/php-config7 /usr/bin/php-config
RUN ln -s /usr/bin/phpize7 /usr/bin/phpize

RUN apt-get update && apt-get install -yq git sudo wget unzip

## if PDO is needed
#RUN docker-php-ext-install pdo_mysql

## if GD is needed
# RUN docker-php-ext-install gd

ADD ./bin/install_composer.sh ./install_composer.sh
RUN chmod +x ./install_composer.sh && ./install_composer.sh
RUN cp ./composer.phar /usr/bin/composer

COPY ./var/etc/apache/apache2.conf /etc/apache2/apache2.conf
COPY ./var/etc/apache/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

ADD ./bin/install_node.sh ./install_node.sh
RUN chmod +x ./install_node.sh && ./install_node.sh

COPY ./ /var/www/html

RUN sudo npm install
RUN sudo npm run production
RUN rm -Rf node_modules

RUN chmod +x /var/www/html/bin/deploy.sh
RUN chmod +x /var/www/html/bin/EntryPoint.sh
RUN chmod +x /var/www/html/App/Cli/bin/cli
RUN /var/www/html/bin/deploy.sh docker

WORKDIR /var/www/html

EXPOSE 80
CMD ["bin/EntryPoint.sh"]
