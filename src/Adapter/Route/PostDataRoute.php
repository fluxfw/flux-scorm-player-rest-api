<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Body\TextBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteContentTypeDocumentationDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteParamDocumentationDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Status\DefaultStatus;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class PostDataRoute implements Route
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
        if (!($request->getParsedBody() instanceof JsonBodyDto)) {
            return ServerResponseDto::new(
                TextBodyDto::new(
                    "No json body"
                ),
                DefaultStatus::_400
            );
        }

        $data = $this->scorm_player_api->storeData(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "user_id"
            ),
            $request->getParsedBody()->getData()
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
