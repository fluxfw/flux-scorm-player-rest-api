<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class DeleteRoute implements Route
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
        return DefaultMethod::DELETE;
    }


    public function getRoute() : string
    {
        return "/delete/{scorm_id}";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        $this->scorm_player_api->deleteScormPackage(
            $request->getParam(
                "scorm_id"
            )
        );

        return null;
    }
}
