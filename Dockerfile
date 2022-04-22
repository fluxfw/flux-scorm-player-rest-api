ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api
ARG FLUX_SCORM_PLAYER_API_IMAGE=docker-registry.fluxpublisher.ch/flux-scorm-player/api

FROM $FLUX_AUTOLOAD_API_IMAGE:latest AS flux_autoload_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_autoload_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxAutoloadApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerRestApi\\Libs\\FluxAutoloadApi
COPY --from=flux_autoload_api /flux-autoload-api /code
RUN /flux-namespace-changer/bin/docker-entrypoint.php

FROM $FLUX_REST_API_IMAGE:latest AS flux_rest_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_rest_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxRestApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerRestApi\\Libs\\FluxRestApi
COPY --from=flux_rest_api /flux-rest-api /code
RUN /flux-namespace-changer/bin/docker-entrypoint.php

FROM $FLUX_SCORM_PLAYER_API_IMAGE:latest AS flux_scorm_player_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_scorm_player_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxScormPlayerApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerRestApi\\Libs\\FluxScormPlayerApi
COPY --from=flux_scorm_player_api /flux-scorm-player-api /code
RUN /flux-namespace-changer/bin/docker-entrypoint.php

FROM alpine:latest AS build

COPY --from=flux_autoload_api_build /code /flux-scorm-player-rest-api/libs/flux-autoload-api
COPY --from=flux_rest_api_build /code /flux-scorm-player-rest-api/libs/flux-rest-api
COPY --from=flux_scorm_player_api_build /code /flux-scorm-player-rest-api/libs/flux-scorm-player-api
COPY . /flux-scorm-player-rest-api

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

COPY --from=build /flux-scorm-player-rest-api /flux-scorm-player-rest-api

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"
