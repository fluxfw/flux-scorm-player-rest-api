<?php

namespace FluxScormPlayerRestApi\Adapter\Server;

use FluxRestApi\Adapter\Route\Collector\RouteCollector;
use FluxScormPlayerRestApi\Adapter\Api\ScormPlayerRestApi;
use FluxScormPlayerRestApi\Adapter\Route\AssetRoute;
use FluxScormPlayerRestApi\Adapter\Route\DeleteRoute;
use FluxScormPlayerRestApi\Adapter\Route\GetDataRoute;
use FluxScormPlayerRestApi\Adapter\Route\PlayRoute;
use FluxScormPlayerRestApi\Adapter\Route\PostDataRoute;
use FluxScormPlayerRestApi\Adapter\Route\StaticRoute;
use FluxScormPlayerRestApi\Adapter\Route\UploadRoute;

class ScormPlayerRestApiServerRouteCollector implements RouteCollector
{

    private function __construct(
        private readonly ScormPlayerRestApi $scorm_player_rest_api
    ) {

    }


    public static function new(
        ScormPlayerRestApi $scorm_player_rest_api
    ) : static {
        return new static(
            $scorm_player_rest_api
        );
    }


    public function collectRoutes() : array
    {
        return [
            AssetRoute::new(
                $this->scorm_player_rest_api
            ),
            DeleteRoute::new(
                $this->scorm_player_rest_api
            ),
            GetDataRoute::new(
                $this->scorm_player_rest_api
            ),
            PlayRoute::new(
                $this->scorm_player_rest_api
            ),
            PostDataRoute::new(
                $this->scorm_player_rest_api
            ),
            StaticRoute::new(
                $this->scorm_player_rest_api
            ),
            UploadRoute::new(
                $this->scorm_player_rest_api
            )
        ];
    }
}
