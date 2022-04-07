<?php

namespace FluxScormPlayerRestApi\Adapter\Server;

use FluxScormPlayerRestApi\Adapter\Route\AssetRoute;
use FluxScormPlayerRestApi\Adapter\Route\DeleteRoute;
use FluxScormPlayerRestApi\Adapter\Route\GetDataRoute;
use FluxScormPlayerRestApi\Adapter\Route\PlayRoute;
use FluxScormPlayerRestApi\Adapter\Route\PostDataRoute;
use FluxScormPlayerRestApi\Adapter\Route\StaticRoute;
use FluxScormPlayerRestApi\Adapter\Route\UploadRoute;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Collector\RouteCollector;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class ScormPlayerRestApiServerRouteCollector implements RouteCollector
{

    private function __construct(
        private readonly ScormPlayerApi $scorm_player_api
    ) {

    }


    public static function new(
        ScormPlayerApi $scorm_player_api
    ) : static {
        return new static(
            $scorm_player_api
        );
    }


    public function collectRoutes() : array
    {
        return [
            AssetRoute::new(
                $this->scorm_player_api
            ),
            DeleteRoute::new(
                $this->scorm_player_api
            ),
            GetDataRoute::new(
                $this->scorm_player_api
            ),
            PlayRoute::new(
                $this->scorm_player_api
            ),
            PostDataRoute::new(
                $this->scorm_player_api
            ),
            StaticRoute::new(
                $this->scorm_player_api
            ),
            UploadRoute::new(
                $this->scorm_player_api
            )
        ];
    }
}
