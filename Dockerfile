FROM node:19-alpine AS npm

RUN (mkdir -p /build/flux-scorm-player-rest-api/libs/scorm-again && cd /build/flux-scorm-player-rest-api/libs/scorm-again && npm install scorm-again@1.7.1)

FROM php:8.2-cli-alpine AS build

RUN apk add --no-cache coreutils

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN (mkdir -p /build/flux-scorm-player-rest-api/libs/mongo-php-library && cd /build/flux-scorm-player-rest-api/libs/mongo-php-library && composer require mongodb/mongodb:1.15.0 --ignore-platform-reqs && cd vendor/mongodb/mongodb && rm -rf $(ls -A -I "composer*" -I "LICENSE*" -I src))

COPY --from=npm /build/flux-scorm-player-rest-api/libs/scorm-again /build/flux-scorm-player-rest-api/libs/scorm-again
RUN (cd /build/flux-scorm-player-rest-api/libs/scorm-again/node_modules/scorm-again && rm -rf $(ls -A -I dist -I "LICENSE*" -I "package*") && cd dist && rm -rf $(ls -A -I "*.min.js"))

RUN (mkdir -p /build/flux-scorm-player-rest-api/libs/flux-file-storage-api && cd /build/flux-scorm-player-rest-api/libs/flux-file-storage-api && wget -O - https://github.com/fluxfw/flux-file-storage-api/archive/refs/tags/v2023-01-30-1.tar.gz | tar -xz --strip-components=1)

RUN (mkdir -p /build/flux-scorm-player-rest-api/libs/flux-rest-api && cd /build/flux-scorm-player-rest-api/libs/flux-rest-api && wget -O - https://github.com/fluxfw/flux-rest-api/archive/refs/tags/v2023-01-30-1.tar.gz | tar -xz --strip-components=1)

RUN (mkdir -p /build/flux-scorm-player-rest-api/libs/flux-scorm-player-api && cd /build/flux-scorm-player-rest-api/libs/flux-scorm-player-api && wget -O - https://github.com/fluxfw/flux-scorm-player-api/archive/refs/tags/v2023-01-30-1.tar.gz | tar -xz --strip-components=1)

COPY . /build/flux-scorm-player-rest-api

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

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"
