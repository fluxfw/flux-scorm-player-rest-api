<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Body\JsonBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Request\RequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Response\ResponseDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Status\DefaultStatus;
use FluxScormPlayerRestApi\Libs\FluxScormPlayerApi\Adapter\Api\ScormPlayerApi;

class GetDataRoute implements Route
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
        return "/data/{scorm_id}/{user_id}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $data = $this->scorm_player_api->getData(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "user_id"
            )
        );

        if ($data !== null) {
            return ResponseDto::new(
                JsonBodyDto::new(
                    $data
                )
            );
        } else {
            return ResponseDto::new(
                null,
                DefaultStatus::_403
            );
        }
    }
}
