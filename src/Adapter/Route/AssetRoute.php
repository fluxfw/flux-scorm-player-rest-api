<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Status\DefaultStatus;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class AssetRoute implements Route
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


    public function getDocuRequestBodyTypes() : ?array
    {
        return null;
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : Method
    {
        return DefaultMethod::GET;
    }


    public function getRoute() : string
    {
        return "/asset/{scorm_id}{path.}";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        $path = $this->scorm_player_api->getScormPackageAssetPath(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "path"
            )
        );

        if ($path !== null) {
            return ServerResponseDto::new(
                null,
                null,
                null,
                null,
                $path
            );
        } else {
            return ServerResponseDto::new(
                null,
                DefaultStatus::_404
            );
        }
    }
}
