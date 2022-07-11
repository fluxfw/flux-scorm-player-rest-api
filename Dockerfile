ARG FLUX_AUTOLOAD_API_IMAGE
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer
ARG FLUX_REST_API_IMAGE
ARG FLUX_SCORM_PLAYER_API_IMAGE

FROM $FLUX_AUTOLOAD_API_IMAGE:v2022-06-22-1 AS flux_autoload_api
FROM $FLUX_REST_API_IMAGE:v2022-06-29-2 AS flux_rest_api
FROM $FLUX_SCORM_PLAYER_API_IMAGE:v2022-07-11-1 AS flux_scorm_player_api

FROM $FLUX_NAMESPACE_CHANGER_IMAGE:v2022-06-23-1 AS build_namespaces

COPY --from=flux_autoload_api /flux-autoload-api /code/flux-autoload-api
RUN change-namespace /code/flux-autoload-api FluxAutoloadApi FluxScormPlayerRestApi\\Libs\\FluxAutoloadApi

COPY --from=flux_rest_api /flux-rest-api /code/flux-rest-api
RUN change-namespace /code/flux-rest-api FluxRestApi FluxScormPlayerRestApi\\Libs\\FluxRestApi

COPY --from=flux_scorm_player_api /flux-scorm-player-api /code/flux-scorm-player-api
RUN change-namespace /code/flux-scorm-player-api FluxScormPlayerApi FluxScormPlayerRestApi\\Libs\\FluxScormPlayerApi

FROM alpine:latest AS build

COPY --from=build_namespaces /code/flux-autoload-api /build/flux-scorm-player-rest-api/libs/flux-autoload-api
COPY --from=build_namespaces /code/flux-rest-api /build/flux-scorm-player-rest-api/libs/flux-rest-api
COPY --from=build_namespaces /code/flux-scorm-player-api /build/flux-scorm-player-rest-api/libs/flux-scorm-player-api
COPY . /build/flux-scorm-player-rest-api

RUN (cd /build && tar -czf flux-scorm-player-rest-api.tar.gz flux-scorm-player-rest-api)

FROM php:8.1-cli-alpine

LABEL org.opencontainers.image.source="https://github.com/flux-caps/flux-scorm-player-rest-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libstdc++ libzip && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev libzip-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/mongodb && cd /usr/src/php/ext/mongodb && wget -O - https://pecl.php.net/get/mongodb | tar -xz --strip-components=1) && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - https://pecl.php.net/get/swoole | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl --enable-swoole-json && \
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
