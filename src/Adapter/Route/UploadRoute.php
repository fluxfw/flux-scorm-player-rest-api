<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxRestApi\Adapter\Body\FormDataBodyDto;
use FluxRestApi\Adapter\Body\TextBodyDto;
use FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxRestApi\Adapter\Method\DefaultMethod;
use FluxRestApi\Adapter\Method\Method;
use FluxRestApi\Adapter\Route\Documentation\RouteContentTypeDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxRestApi\Adapter\Route\Route;
use FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxRestApi\Adapter\Status\DefaultStatus;
use FluxScormPlayerRestApi\Adapter\Api\ScormPlayerRestApi;

class UploadRoute implements Route
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
            "Upload scorm package",
            null,
            null,
            null,
            [
                RouteContentTypeDocumentationDto::new(
                    DefaultBodyType::FORM_DATA_2,
                    "object",
                    "Scorm package"
                )
            ],
            [
                RouteResponseDocumentationDto::new(),
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::TEXT,
                    DefaultStatus::_400,
                    null,
                    "No form data body"
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
        return "/upload";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        if (!($request->parsed_body instanceof FormDataBodyDto)) {
            return ServerResponseDto::new(
                TextBodyDto::new(
                    "No form data body"
                ),
                DefaultStatus::_400
            );
        }

        $this->scorm_player_rest_api->uploadScormPackage(
            $request->parsed_body->data["id"],
            $request->parsed_body->data["title"],
            $request->parsed_body->files["file"]["tmp_name"]
        );

        return null;
    }
}
