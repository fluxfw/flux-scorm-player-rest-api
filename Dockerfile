ARG ALPINE_IMAGE=alpine:latest
ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api:latest
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer:latest
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api:latest
ARG FLUX_SCORM_PLAYER_API=docker-registry.fluxpublisher.ch/flux-scorm-player/api:latest
ARG MONGODB_SOURCE_URL=https://pecl.php.net/get/mongodb
ARG PHP_CLI_IMAGE=php:cli-alpine
ARG SWOOLE_SOURCE_URL=https://pecl.php.net/get/swoole

FROM $FLUX_AUTOLOAD_API_IMAGE AS flux_autoload_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_autoload_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxAutoloadApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerRestApi\\Libs\\FluxAutoloadApi
COPY --from=flux_autoload_api /flux-autoload-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $FLUX_REST_API_IMAGE AS flux_rest_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_rest_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxRestApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerRestApi\\Libs\\FluxRestApi
COPY --from=flux_rest_api /flux-rest-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $FLUX_SCORM_PLAYER_API AS flux_scorm_player_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_scorm_player_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxScormPlayerApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerRestApi\\Libs\\FluxScormPlayerApi
COPY --from=flux_scorm_player_api /flux-scorm-player-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $ALPINE_IMAGE AS build

COPY --from=flux_autoload_api_build /code /flux-scorm-player-rest-api/libs/flux-autoload-api
COPY --from=flux_rest_api_build /code /flux-scorm-player-rest-api/libs/flux-rest-api
COPY --from=flux_scorm_player_api_build /code /flux-scorm-player-rest-api/libs/flux-scorm-player-api
COPY . /flux-scorm-player-rest-api

FROM $PHP_CLI_IMAGE
ARG MONGODB_SOURCE_URL
ARG SWOOLE_SOURCE_URL

LABEL org.opencontainers.image.source="https://github.com/flux-caps/flux-scorm-player-rest-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libstdc++ libzip && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev libzip-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/mongodb && cd /usr/src/php/ext/mongodb && wget -O - $MONGODB_SOURCE_URL | tar -xz --strip-components=1) && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - $SWOOLE_SOURCE_URL | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl --enable-swoole-json && \
    docker-php-ext-install -j$(nproc) mongodb swoole zip && \
    docker-php-source delete && \
    apk del .build-deps

RUN mkdir -p /scorm && chown www-data:www-data -R /scorm
VOLUME /scorm

USER www-data:www-data

EXPOSE 9501

ENTRYPOINT ["/flux-scorm-player-rest-api/bin/server.php"]

COPY --from=build /flux-scorm-player-rest-api /flux-scorm-player-rest-api
