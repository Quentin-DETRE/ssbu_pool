FROM phpdockerio/php:8.1-cli
WORKDIR "/var/www"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.1-mysql \
        php8.1-pgsql \
        php8.1-sqlite3 \
        php8.1-bcmath \
        php8.1-bz2 \
        php8.1-dba \
        php8.1-enchant \
        php8.1-gd \
        php8.1-gmp \
        php8.1-imap \
        php8.1-intl \
        php8.1-igbinary \
        php8.1-imagick \
        wget \
        curl \
        nano \
        git \
        make \
        cron \
        sendmail \
        bash-completion \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./configs/etc/php/8.1/cli/conf.d/php-override.ini /etc/php/8.1/cli/conf.d/99_php-override.ini

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Symfony cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt update && apt install symfony-cli
RUN symfony server:ca:install

# besoin d'un utilisateur afin de ne pas etre en root et ne pas créer de soucis
RUN adduser --gecos '' --disabled-password php \
    && chown -R php:php /var/www

CMD ["symfony", "server:start","--port","80"]
