<?php

namespace FluxScormPlayerRestApi\Service\Filesystem\Command;

use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApi;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerRestApi\Service\Filesystem\FilesystemUtils;

class GetScormPackageMetadataCommand
{

    use FilesystemUtils;

    private function __construct(
        private readonly FileStorageRestApi $file_storage_rest_api,
        private readonly MetadataStorage $metadata_storage
    ) {

    }


    public static function new(
        FileStorageRestApi $file_storage_rest_api,
        MetadataStorage $metadata_storage
    ) : static {
        return new static(
            $file_storage_rest_api,
            $metadata_storage
        );
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        $id = $this->normalizeId(
            $id
        );

        if (!$this->file_storage_rest_api->exists(
            $id
        )
        ) {
            return null;
        }

        return $this->metadata_storage->getMetadata(
            $id
        );
    }
}
