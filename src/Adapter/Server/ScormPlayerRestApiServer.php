<?php

namespace FluxScormPlayerRestApi\Adapter\Server;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Collector\FolderRouteCollector;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Handler\SwooleHandler;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;
use Swoole\Http\Server;

class ScormPlayerRestApiServer
{

    private function __construct(
        private readonly ScormPlayerRestApiServerConfigDto $scorm_player_rest_api_server_config,
        private readonly SwooleHandler $swoole_handler
    ) {

    }


    public static function new(
        ?ScormPlayerRestApiServerConfigDto $scorm_player_rest_api_server_config = null
    ) : static {
        $scorm_player_rest_api_server_config ??= ScormPlayerRestApiServerConfigDto::newFromEnv();

        return new static(
            $scorm_player_rest_api_server_config,
            SwooleHandler::new(
                FolderRouteCollector::new(
                    __DIR__ . "/../Route",
                    [
                        ScormPlayerApi::new(
                            $scorm_player_rest_api_server_config->scorm_player_api_config
                        )
                    ]
                )
            )
        );
    }


    public function init() : void
    {
        $options = [
            "package_max_length" => $this->scorm_player_rest_api_server_config->max_upload_size
        ];
        $sock_type = SWOOLE_TCP;

        if ($this->scorm_player_rest_api_server_config->https_cert !== null) {
            $options += [
                "ssl_cert_file" => $this->scorm_player_rest_api_server_config->https_cert,
                "ssl_key_file"  => $this->scorm_player_rest_api_server_config->https_key
            ];
            $sock_type += SWOOLE_SSL;
        }

        $server = new Server($this->scorm_player_rest_api_server_config->listen, $this->scorm_player_rest_api_server_config->port, SWOOLE_PROCESS, $sock_type);

        $server->set($options);

        $server->on("request", [$this->swoole_handler, "handle"]);

        $server->start();
    }
}
