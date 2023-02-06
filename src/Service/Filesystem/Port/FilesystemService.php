<?php

namespace FluxScormPlayerRestApi\Service\Filesystem\Port;

use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApi;
use FluxScormPlayerRestApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerRestApi\Service\Filesystem\Command\DeleteScormPackageCommand;
use FluxScormPlayerRestApi\Service\Filesystem\Command\GetScormPackageAssetPathCommand;
use FluxScormPlayerRestApi\Service\Filesystem\Command\GetScormPackageMetadataCommand;
use FluxScormPlayerRestApi\Service\Filesystem\Command\UploadScormPackageCommand;

class FilesystemService
{

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
        DeleteScormPackageCommand::new(
            $this->file_storage_rest_api,
            $this->metadata_storage,
            $this->data_storage
        )
            ->deleteScormPackage(
                $id
            );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        return GetScormPackageAssetPathCommand::new(
            $this->file_storage_rest_api
        )
            ->getScormPackageAssetPath(
                $id,
                $path
            );
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        return GetScormPackageMetadataCommand::new(
            $this->file_storage_rest_api,
            $this->metadata_storage
        )
            ->getScormPackageMetadata(
                $id
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        UploadScormPackageCommand::new(
            $this->file_storage_rest_api,
            $this->metadata_storage
        )
            ->uploadScormPackage(
                $id,
                $title,
                $file
            );
    }
}
