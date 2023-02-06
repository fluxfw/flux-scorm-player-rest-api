<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxRestApi\Adapter\Body\HtmlBodyDto;
use FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxRestApi\Adapter\Method\DefaultMethod;
use FluxRestApi\Adapter\Method\Method;
use FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteParamDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxRestApi\Adapter\Route\Route;
use FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxRestApi\Adapter\Status\DefaultStatus;
use FluxScormPlayerRestApi\Adapter\Api\ScormPlayerRestApi;

class PlayRoute implements Route
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


    public function getDocumentation() : ?RouteDocumentationDto
    {
        return RouteDocumentationDto::new(
            $this->getRoute(),
            $this->getMethod(),
            "Get play scorm package UI",
            null,
            [
                RouteParamDocumentationDto::new(
                    "scorm_id",
                    "string",
                    "Scorm package id"
                ),
                RouteParamDocumentationDto::new(
                    "user_id",
                    "string",
                    "User id"
                )
            ],
            null,
            null,
            [
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::HTML,
                    null,
                    null,
                    "Play scorm package UI"

                ),
                RouteResponseDocumentationDto::new(
                    null,
                    DefaultStatus::_403,
                    null,
                    "Scorm package not available"
                )
            ]
        );
    }


    public function getMethod() : Method
    {
        return DefaultMethod::GET;
    }


    public function getRoute() : string
    {
        return "/play/{scorm_id}/{user_id}";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        $html = $this->scorm_player_rest_api->playScormPackage(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "user_id"
            )
        );

        if ($html !== null) {
            return ServerResponseDto::new(
                HtmlBodyDto::new(
                    $html
                )
            );
        } else {
            return ServerResponseDto::new(
                null,
                DefaultStatus::_403
            );
        }
    }
}
