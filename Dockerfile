FROM php:7.1

RUN apt update && apt install -y \
        git \
        zlib1g-dev \
    && docker-php-ext-install -j$(nproc) zip

ENV COMPOSER_HOME /composer

ENV COMPOSER_ALLOW_SUPERUSER 1

# Setup the Composer installer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

COPY . /var/www/card-battle-game

WORKDIR /var/www/card-battle-game

CMD ["sh", "create.sh"]
