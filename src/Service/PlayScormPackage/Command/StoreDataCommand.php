<?php

namespace FluxScormPlayerRestApi\Service\PlayScormPackage\Command;

use FluxScormPlayerRestApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerRestApi\Service\Filesystem\Port\FilesystemService;

class StoreDataCommand
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


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        $metadata = $this->filesystem_service->getScormPackageMetadata(
            $id
        );

        if ($metadata === null) {
            return null;
        }

        $this->data_storage->storeData(
            $id,
            $user_id,
            $data
        );

        return $data;
    }
}
