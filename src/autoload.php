<?php

namespace FluxScormPlayerRestApi;

require_once __DIR__ . "/../../flux-file-storage-api/autoload.php";

require_once __DIR__ . "/../../flux-rest-api/autoload.php";

require_once __DIR__ . "/../../flux-scorm-player-api/autoload.php";

require_once __DIR__ . "/../../mongo-php-library/vendor/autoload.php";

spl_autoload_register(function (string $class) : void {
    if (str_starts_with($class, __NAMESPACE__ . "\\")) {
        require_once __DIR__ . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
    }
});
