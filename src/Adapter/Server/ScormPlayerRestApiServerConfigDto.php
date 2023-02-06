<?php

namespace FluxScormPlayerRestApi\Adapter\Server;

use FluxScormPlayerRestApi\Adapter\Api\ScormPlayerRestApiConfigDto;

class ScormPlayerRestApiServerConfigDto
{

    private function __construct(
        public readonly ScormPlayerRestApiConfigDto $scorm_player_rest_api_config,
        public readonly ?string $https_cert,
        public readonly ?string $https_key,
        public readonly string $listen,
        public readonly int $port,
        public readonly int $max_upload_size
    ) {

    }


    public static function new(
        ScormPlayerRestApiConfigDto $scorm_player_rest_api_config,
        ?string $https_cert = null,
        ?string $https_key = null,
        ?string $listen = null,
        ?int $port = null,
        ?int $max_upload_size = null
    ) : static {
        return new static(
            $scorm_player_rest_api_config,
            $https_cert,
            $https_key,
            $listen ?? "0.0.0.0",
            $port ?? 9501,
            $max_upload_size ?? 104857600
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            ScormPlayerRestApiConfigDto::newFromEnv(),
            $_ENV["FLUX_SCORM_PLAYER_REST_API_SERVER_HTTPS_CERT"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_SERVER_HTTPS_KEY"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_SERVER_LISTEN"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_SERVER_PORT"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_SERVER_MAX_UPLOAD_SIZE"] ?? null
        );
    }
}
