<?php

namespace FluxScormPlayerRestApi;

require_once __DIR__ . "/../libs/flux-autoload-api/autoload.php";
require_once __DIR__ . "/../libs/flux-rest-api/autoload.php";
require_once __DIR__ . "/../libs/flux-scorm-player-api/autoload.php";

use FluxScormPlayerRestApi\Libs\FluxAutoloadApi\Adapter\Autoload\Psr4Autoload;
use FluxScormPlayerRestApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpExtChecker;
use FluxScormPlayerRestApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpVersionChecker;

PhpVersionChecker::new(
    ">=8.2"
)
    ->checkAndDie(
        __NAMESPACE__
    );
PhpExtChecker::new(
    [
        "swoole"
    ]
)
    ->checkAndDie(
        __NAMESPACE__
    );

Psr4Autoload::new(
    [
        __NAMESPACE__ => __DIR__
    ]
)
    ->autoload();
