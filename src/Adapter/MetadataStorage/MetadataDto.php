<?php

namespace FluxScormPlayerRestApi\Adapter\MetadataStorage;

class MetadataDto
{

    private function __construct(
        public readonly string $title,
        public readonly string $entrypoint,
        public readonly MetadataType $type
    ) {

    }


    public static function new(
        string $title,
        string $entrypoint,
        MetadataType $type
    ) : static {
        return new static(
            $title,
            $entrypoint,
            $type
        );
    }
}
