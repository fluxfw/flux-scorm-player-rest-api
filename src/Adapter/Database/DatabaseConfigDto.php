<?php

namespace FluxScormPlayerRestApi\Adapter\Database;

use SensitiveParameter;

class DatabaseConfigDto
{

    private function __construct(
        public readonly string $password,
        public readonly string $host,
        public readonly int $port,
        public readonly string $user,
        public readonly string $database
    ) {

    }


    public static function new(
        #[SensitiveParameter] string $password,
        ?string $host = null,
        ?int $port = null,
        ?string $user = null,
        ?string $database = null
    ) : static {
        return new static(
            $password,
            $host ?? "scorm-player-database",
            $port ?? 27017,
            $user ?? "scorm-player",
            $database ?? "scorm-player"
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            ($_ENV["FLUX_SCORM_PLAYER_REST_API_DATABASE_PASSWORD"] ?? null) ??
            (($password_file = $_ENV["FLUX_SCORM_PLAYER_REST_API_DATABASE_PASSWORD_FILE"] ?? null) !== null && file_exists($password_file) ? rtrim(file_get_contents($password_file) ?: "", "\n\r") : null),
            $_ENV["FLUX_SCORM_PLAYER_REST_API_DATABASE_HOST"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_DATABASE_PORT"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_DATABASE_USER"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_REST_API_DATABASE_DATABASE"] ?? null
        );
    }
}
