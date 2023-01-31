FROM php:8.2-cli-alpine AS build

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY bin/install-libraries.sh /build/flux-scorm-player-rest-api/libs/flux-scorm-player-rest-api/bin/install-libraries.sh
RUN /build/flux-scorm-player-rest-api/libs/flux-scorm-player-rest-api/bin/install-libraries.sh

RUN ln -s libs/flux-scorm-player-rest-api/bin /build/flux-scorm-player-rest-api/bin

COPY . /build/flux-scorm-player-rest-api/libs/flux-scorm-player-rest-api

FROM php:8.2-cli-alpine

RUN apk add --no-cache libstdc++ libzip && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev libzip-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/mongodb && cd /usr/src/php/ext/mongodb && wget -O - https://pecl.php.net/get/mongodb | tar -xz --strip-components=1) && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - https://pecl.php.net/get/swoole | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl && \
    docker-php-ext-install -j$(nproc) mongodb swoole zip && \
    docker-php-source delete && \
    apk del .build-deps

RUN mkdir -p /scorm && chown www-data:www-data -R /scorm
VOLUME /scorm

USER www-data:www-data

EXPOSE 9501

ENTRYPOINT ["/flux-scorm-player-rest-api/bin/server.php"]

COPY --from=build /build /
