<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxRestApi\Adapter\Method\DefaultMethod;
use FluxRestApi\Adapter\Method\Method;
use FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteParamDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxRestApi\Adapter\Route\Route;
use FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

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


    public function getDocumentation() : ?RouteDocumentationDto
    {
        return RouteDocumentationDto::new(
            $this->getRoute(),
            $this->getMethod(),
            "Delete scorm package",
            null,
            [
                RouteParamDocumentationDto::new(
                    "scorm_id",
                    "string",
                    "Scorm package id"
                )
            ],
            null,
            null,
            [
                RouteResponseDocumentationDto::new()
            ]
        );
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
