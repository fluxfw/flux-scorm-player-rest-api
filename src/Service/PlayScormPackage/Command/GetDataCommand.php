<?php

namespace FluxScormPlayerRestApi\Service\PlayScormPackage\Command;

use FluxScormPlayerRestApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerRestApi\Service\Filesystem\Port\FilesystemService;

class GetDataCommand
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
        $metadata = $this->filesystem_service->getScormPackageMetadata(
            $id
        );

        if ($metadata === null) {
            return null;
        }

        return $this->data_storage->getData(
                $id,
                $user_id
            ) ?? (object) [];
    }
}
