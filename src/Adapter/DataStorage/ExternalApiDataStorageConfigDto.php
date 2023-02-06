<?php

namespace FluxScormPlayerRestApi\Adapter\DataStorage;

class ExternalApiDataStorageConfigDto
{

    private function __construct(
        public readonly string $get_data_url,
        public readonly string $store_data_url,
        public readonly string $delete_data_url
    ) {

    }


    public static function new(
        string $get_data_url,
        string $store_data_url,
        string $delete_data_url
    ) : static {
        return new static(
            $get_data_url,
            $store_data_url,
            $delete_data_url
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            $_ENV["FLUX_SCORM_PLAYER_REST_API_EXTERNAL_API_GET_DATA_URL"],
            $_ENV["FLUX_SCORM_PLAYER_REST_API_EXTERNAL_API_STORE_DATA_URL"],
            $_ENV["FLUX_SCORM_PLAYER_REST_API_EXTERNAL_API_DELETE_DATA_URL"]
        );
    }
}
