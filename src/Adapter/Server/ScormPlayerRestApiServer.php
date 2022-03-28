<?php

namespace FluxScormPlayerRestApi\Adapter\Server;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\SwooleRestApiServer;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\SwooleRestApiServerConfigDto;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class ScormPlayerRestApiServer
{

    private function __construct(
        private readonly SwooleRestApiServer $swoole_rest_api_server
    ) {

    }


    public static function new(
        ?ScormPlayerRestApiServerConfigDto $scorm_player_rest_api_server_config = null
    ) : static {
        $scorm_player_rest_api_server_config ??= ScormPlayerRestApiServerConfigDto::newFromEnv();

        return new static(
            SwooleRestApiServer::new(
                ScormPlayerRestApiServerRouteCollector::new(
                    ScormPlayerApi::new(
                        $scorm_player_rest_api_server_config->scorm_player_api_config
                    )
                ),
                null,
                SwooleRestApiServerConfigDto::new(
                    $scorm_player_rest_api_server_config->https_cert,
                    $scorm_player_rest_api_server_config->https_key,
                    $scorm_player_rest_api_server_config->listen,
                    $scorm_player_rest_api_server_config->port,
                    $scorm_player_rest_api_server_config->max_upload_size
                )
            )
        );
    }


    public function init() : void
    {
        $this->swoole_rest_api_server->init();
    }
}
