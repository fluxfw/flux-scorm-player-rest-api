<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Body\DefaultBodyType;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Body\JsonBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Body\TextBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Method\Method;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Request\RequestDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Response\ResponseDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Route\Route;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Status\DefaultStatus;
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


    public function getDocuRequestBodyTypes() : ?array
    {
        return [
            DefaultBodyType::JSON
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
        return "/data/{scorm_id}/{user_id}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        if (!($request->getParsedBody() instanceof JsonBodyDto)) {
            return ResponseDto::new(
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
