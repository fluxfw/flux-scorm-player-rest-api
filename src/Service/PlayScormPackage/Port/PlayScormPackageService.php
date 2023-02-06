<?php

namespace FluxScormPlayerRestApi\Service\PlayScormPackage\Port;

use FluxScormPlayerRestApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerRestApi\Service\Filesystem\Port\FilesystemService;
use FluxScormPlayerRestApi\Service\PlayScormPackage\Command\GetDataCommand;
use FluxScormPlayerRestApi\Service\PlayScormPackage\Command\GetStaticPathCommand;
use FluxScormPlayerRestApi\Service\PlayScormPackage\Command\PlayScormPackageCommand;
use FluxScormPlayerRestApi\Service\PlayScormPackage\Command\StoreDataCommand;

class PlayScormPackageService
{

    private function __construct(
        private readonly FilesystemService $filesystem_service,
        private readonly DataStorage $data_storage
    ) {

    }


    public static function new(
        FilesystemService $filesystem_service,
        DataStorage $data_storage
    ) : static {
        return new static(
            $filesystem_service,
            $data_storage
        );
    }


    public function getData(string $id, string $user_id) : ?object
    {
        return GetDataCommand::new(
            $this->filesystem_service,
            $this->data_storage
        )
            ->getData(
                $id,
                $user_id
            );
    }


    public function getStaticPath(string $path) : ?string
    {
        return GetStaticPathCommand::new()
            ->getStaticPath(
                $path
            );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        return PlayScormPackageCommand::new(
            $this->filesystem_service
        )
            ->playScormPackage(
                $id,
                $user_id
            );
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        return StoreDataCommand::new(
            $this->filesystem_service,
            $this->data_storage
        )
            ->storeData(
                $id,
                $user_id,
                $data
            );
    }
}
