<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxRestApi\Adapter\Body\TextBodyDto;
use FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxRestApi\Adapter\Method\DefaultMethod;
use FluxRestApi\Adapter\Method\Method;
use FluxRestApi\Adapter\Route\Documentation\RouteContentTypeDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteParamDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxRestApi\Adapter\Route\Route;
use FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxRestApi\Adapter\Status\DefaultStatus;
use FluxScormPlayerRestApi\Adapter\Api\ScormPlayerRestApi;

class PostDataRoute implements Route
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
            "Store scorm package user data",
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
            [
                RouteContentTypeDocumentationDto::new(
                    DefaultBodyType::JSON,
                    "object",
                    "Scorm package user data"
                )
            ],
            [
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::JSON,
                    null,
                    "object",
                    "Scorm package user data"

                ),
                RouteResponseDocumentationDto::new(
                    null,
                    DefaultStatus::_403,
                    null,
                    "Scorm package not available"
                ),
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::TEXT,
                    DefaultStatus::_400,
                    null,
                    "No json body"
                )
            ]
        );
    }


    public function getMethod() : Method
    {
        return DefaultMethod::POST;
    }


    public function getRoute() : string
    {
        return "/data/{scorm_id}/{user_id}";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        if (!($request->parsed_body instanceof JsonBodyDto)) {
            return ServerResponseDto::new(
                TextBodyDto::new(
                    "No json body"
                ),
                DefaultStatus::_400
            );
        }

        $data = $this->scorm_player_rest_api->storeData(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "user_id"
            ),
            $request->parsed_body->data
        );

        if ($data !== null) {
            return ServerResponseDto::new(
                JsonBodyDto::new(
                    $data
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
