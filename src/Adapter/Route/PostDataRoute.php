<?php

namespace FluxScormPlayerRestApi\Adapter\Route;

use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Body\TextBodyDto;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;
use FluxScormPlayerRestApi\Libs\FluxRestApi\Adapter\Method\Method;
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
