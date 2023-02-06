<?php

namespace FluxScormPlayerRestApi\Service\PlayScormPackage\Command;

class GetStaticPathCommand
{

    private function __construct()
    {

    }


    public static function new() : static
    {
        return new static();
    }


    public function getStaticPath(string $path) : ?string
    {
        $path = __DIR__ . "/static/" . $path;

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }
}
