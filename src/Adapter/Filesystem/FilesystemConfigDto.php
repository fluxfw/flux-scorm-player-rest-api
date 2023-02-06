<?php

namespace FluxScormPlayerRestApi\Adapter\Filesystem;

class FilesystemConfigDto
{

    private function __construct(
        public readonly string $folder
    ) {

    }


    public static function new(
        ?string $folder = null
    ) : static {
        return new static(
            $folder ?? "/scorm"
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            $_ENV["FLUX_SCORM_PLAYER_REST_API_FILESYSTEM_FOLDER"] ?? null
        );
    }
}
