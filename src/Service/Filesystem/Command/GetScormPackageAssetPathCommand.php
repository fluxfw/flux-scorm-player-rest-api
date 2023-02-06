<?php

namespace FluxScormPlayerRestApi\Service\Filesystem\Command;

use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApi;
use FluxScormPlayerRestApi\Service\Filesystem\FilesystemUtils;

class GetScormPackageAssetPathCommand
{

    use FilesystemUtils;

    private function __construct(
        private readonly FileStorageRestApi $file_storage_rest_api
    ) {

    }


    public static function new(
        FileStorageRestApi $file_storage_rest_api
    ) : static {
        return new static(
            $file_storage_rest_api
        );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        $id = $this->normalizeId(
            $id
        );

        return $this->file_storage_rest_api->getFullPath(
            $id . "/" . $path
        );
    }
}
