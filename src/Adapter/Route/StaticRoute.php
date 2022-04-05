<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Request\RequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Response\ResponseDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Status\DefaultStatus;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class StaticRoute implements Route
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
        return "/static/{path.}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $path = $this->scorm_player_api->getStaticPath(
            $request->getParam(
                "path"
            )
        );

        if ($path !== null) {
            return ResponseDto::new(
                null,
                null,
                null,
                null,
                $path
            );
        } else {
            return ResponseDto::new(
                null,
                DefaultStatus::_404
            );
        }
    }
}
