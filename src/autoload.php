<?php

namespace FluxScormPlayerRestApi;

require_once __DIR__ . "/../libs/flux-file-storage-api/autoload.php";
require_once __DIR__ . "/../libs/flux-rest-api/autoload.php";
require_once __DIR__ . "/../libs/flux-scorm-player-api/autoload.php";
require_once __DIR__ . "/../libs/mongo-php-library/vendor/autoload.php";

spl_autoload_register(function (string $class) : void {
    if (str_starts_with($class, __NAMESPACE__ . "\\")) {
        require_once __DIR__ . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
    }
});
