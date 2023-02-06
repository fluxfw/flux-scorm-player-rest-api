<?php

namespace FluxScormPlayerRestApi\Service\Filesystem;

use Exception;

trait FilesystemUtils
{

    private function normalizeId(string $id) : string
    {
        if (str_contains($id, "/") || str_contains($id, "\\")) {
            throw new Exception("Invalid id " . $id);
        }

        if (empty($id)) {
            throw new Exception("Invalid id");
        }

        return $id;
    }
}
