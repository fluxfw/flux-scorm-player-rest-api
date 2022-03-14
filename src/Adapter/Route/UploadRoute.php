<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Body\FormDataBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Body\TextBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Body\DefaultBodyType;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Status\DefaultStatus;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Request\RequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Response\ResponseDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class UploadRoute implements Route
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
        return [
            DefaultBodyType::FORM_DATA
        ];
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : Method
    {
        return DefaultMethod::POST;
    }


    public function getRoute() : string
    {
        return "/upload";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        if (!($request->getParsedBody() instanceof FormDataBodyDto)) {
            return ResponseDto::new(
                TextBodyDto::new(
                    "No form data body"
                ),
                DefaultStatus::_400
            );
        }

        $this->scorm_player_api->uploadScormPackage(
            $request->getParsedBody()->getData()["id"],
            $request->getParsedBody()->getData()["title"],
            $request->getParsedBody()->getFiles()["file"]["tmp_name"]
        );

        return null;
    }
}
