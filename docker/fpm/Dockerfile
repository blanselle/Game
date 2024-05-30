FROM php:fpm-alpine AS base
FROM mlocati/php-extension-installer:latest AS php-extension-installer
FROM composer:latest AS composer

FROM base AS build

COPY docker/fpm/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY docker/fpm/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY --link --from=php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
		acl \
		file \
		gettext \
	;

RUN set -eux; \
    install-php-extensions \
        apcu \
        opcache \
        intl \
        pdo_pgsql \
    ;

WORKDIR /var/www/html

ARG USER_NAME=symfony-quick-starter
ENV USER_NAME=$USER_NAME
ARG USER_UID=1000
ARG USER_GID=1000

RUN set -eux; \
    mkdir -p var/cache var/log; \
    addgroup -g $USER_GID $USER_NAME; \
    adduser -D -u $USER_UID -G $USER_NAME -s /bin/sh $USER_NAME; \
    chown -R $USER_UID:$USER_GID .;

COPY --from=composer --link /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME="/home/$USER_NAME/.composer"
ENV PATH="${PATH}:$COMPOSER_HOME/vendor/bin"

FROM build AS build_dev

RUN set -eux; \
    apk add --no-cache \
        bash \
    ;

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini";

USER $USER_NAME

ENV PATH="${PATH}:/home/$USER_NAME/.symfony5/bin:/var/www/html/bin:/var/www/html/vendor/bin"

COPY --chmod=775 docker/fpm/bin/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

