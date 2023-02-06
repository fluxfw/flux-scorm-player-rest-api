<?php

namespace FluxScormPlayerRestApi\Service\Filesystem\Command;

use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApi;
use FluxScormPlayerRestApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerRestApi\Service\Filesystem\FilesystemUtils;

class DeleteScormPackageCommand
{

    use FilesystemUtils;

    private function __construct(
        private readonly FileStorageRestApi $file_storage_rest_api,
        private readonly MetadataStorage $metadata_storage,
        private readonly DataStorage $data_storage
    ) {

    }


    public static function new(
        FileStorageRestApi $file_storage_rest_api,
        MetadataStorage $metadata_storage,
        DataStorage $data_storage
    ) : static {
        return new static(
            $file_storage_rest_api,
            $metadata_storage,
            $data_storage
        );
    }


    public function deleteScormPackage(string $id) : void
    {
        $id = $this->normalizeId(
            $id
        );

        $this->file_storage_rest_api->delete(
            $id
        );

        $this->metadata_storage->deleteMetadata(
            $id
        );

        $this->data_storage->deleteData(
            $id
        );
    }
}
