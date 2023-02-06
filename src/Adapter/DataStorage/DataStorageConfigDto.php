<?php

namespace FluxScormPlayerRestApi\Adapter\DataStorage;

class DataStorageConfigDto
{

    public readonly DataStorageConfigType $type;


    public static function new(?DataStorageConfigType $type = null) : static
    {
        $dto = new static();

        $dto->type = $type ?? DataStorageConfigType::DATABASE;

        return $dto;
    }


    public static function newFromEnv() : static
    {
        return static::new(
            ($type = $_ENV["FLUX_SCORM_PLAYER_REST_API_DATA_STORAGE_TYPE"] ?? null) !== null ? DataStorageConfigType::from($type) : null
        );
    }
}
