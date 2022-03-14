#!/usr/bin/env php
<?php

require_once __DIR__ . "/../autoload.php";

use FluxScormPlayerRestApi\Adapter\Server\ScormPlayerRestApiServer;

ScormPlayerRestApiServer::new()
    ->init();
