<?php

namespace FluxScormPlayerRestApi\Adapter\Api;

use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApi;
use FluxFileStorageRestApi\Adapter\Api\FileStorageRestApiConfigDto;
use FluxFileStorageRestApi\Adapter\Storage\StorageConfigDto;
use FluxScormPlayerRestApi\Service\Filesystem\Port\FilesystemService;
use FluxScormPlayerRestApi\Service\PlayScormPackage\Port\PlayScormPackageService;

class ScormPlayerRestApi
{

    private function __construct(
        private readonly ScormPlayerRestApiConfigDto $scorm_player_rest_api_config
    ) {

    }


    public static function new(
        ?ScormPlayerRestApiConfigDto $scorm_player_rest_api_config = null
    ) : static {
        return new static(
            $scorm_player_rest_api_config ?? ScormPlayerRestApiConfigDto::newFromEnv()
        );
    }


    public function deleteScormPackage(string $id) : void
    {
        $this->getFilesystemService()
            ->deleteScormPackage(
                $id
            );
    }


    public function getData(string $id, string $user_id) : ?object
    {
        return $this->getPlayScormPackageService()
            ->getData(
                $id,
                $user_id
            );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        return $this->getFilesystemService()
            ->getScormPackageAssetPath(
                $id,
                $path
            );
    }


    public function getStaticPath(string $path) : ?string
    {
        return $this->getPlayScormPackageService()
            ->getStaticPath(
                $path
            );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        return $this->getPlayScormPackageService()
            ->playScormPackage(
                $id,
                $user_id
            );
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        return $this->getPlayScormPackageService()
            ->storeData(
                $id,
                $user_id,
                $data
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        $this->getFilesystemService()
            ->uploadScormPackage(
                $id,
                $title,
                $file
            );
    }


    private function getFileStorageRestApi() : FileStorageRestApi
    {
        return FileStorageRestApi::new(
            FileStorageRestApiConfigDto::new(
                StorageConfigDto::new(
                    $this->scorm_player_rest_api_config->filesystem_config->folder
                )
            )
        );
    }


    private function getFilesystemService() : FilesystemService
    {
        return FilesystemService::new(
            $this->getFileStorageRestApi(),
            $this->scorm_player_rest_api_config->metadata_storage,
            $this->scorm_player_rest_api_config->data_storage
        );
    }


    private function getPlayScormPackageService() : PlayScormPackageService
    {
        return PlayScormPackageService::new(
            $this->getFilesystemService(),
            $this->scorm_player_rest_api_config->data_storage
        );
    }
}
