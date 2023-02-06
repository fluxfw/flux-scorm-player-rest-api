<?php

namespace FluxScormPlayerRestApi\Service\Filesystem\Command;

use Exception;
use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApi;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerRestApi\Adapter\MetadataStorage\MetadataType;
use FluxScormPlayerRestApi\Service\Filesystem\FilesystemUtils;

class UploadScormPackageCommand
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


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        $id = $this->normalizeId(
            $id
        );

        $this->file_storage_rest_api->upload(
            $file,
            $id . ".zip",
            $id,
            true,
            false,
            true,
            true
        );

        $manifest = json_decode(json_encode(simplexml_load_file($this->file_storage_rest_api->getFullPath(
            $id . "/imsmanifest.xml"
        ))), true);

        $type = match ($manifest["metadata"]["schemaversion"]) {
            "1.2" => MetadataType::_1_2,
            "CAM 1.3", "2004 3rd Edition", "2004 4rd Edition" => MetadataType::_2004,
            default => throw new Exception("Unknown scorm type " . $manifest["metadata"]["schemaversion"])
        };

        $this->metadata_storage->storeMetadata(
            $id,
            MetadataDto::new(
                $title,
                $manifest["resources"]["resource"]["@attributes"]["href"],
                $type
            )
        );
    }
}
