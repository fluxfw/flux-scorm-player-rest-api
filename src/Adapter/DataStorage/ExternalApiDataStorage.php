<?php

namespace FluxScormPlayerRestApi\Adapter\DataStorage;

use FluxRestApi\Adapter\Api\RestApi;
use FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxRestApi\Adapter\Client\ClientRequestDto;
use FluxRestApi\Adapter\Header\DefaultHeaderKey;
use FluxRestApi\Adapter\Method\DefaultMethod;
use FluxRestApi\Adapter\Method\Method;

class ExternalApiDataStorage implements DataStorage
{

    private function __construct(
        private readonly ExternalApiDataStorageConfigDto $external_api_data_storage_config,
        private readonly RestApi $rest_api
    ) {

    }


    public static function new(
        ExternalApiDataStorageConfigDto $external_api_data_storage_config,
        RestApi $rest_api
    ) : static {
        return new static(
            $external_api_data_storage_config,
            $rest_api
        );
    }


    public function deleteData(string $scorm_id) : void
    {
        $this->request(
            $this->external_api_data_storage_config->delete_data_url,
            $scorm_id,
            null,
            DefaultMethod::DELETE
        );
    }


    public function getData(string $scorm_id, string $user_id) : ?object
    {
        return $this->request(
            $this->external_api_data_storage_config->get_data_url,
            $scorm_id,
            $user_id
        );
    }


    public function storeData(string $scorm_id, string $user_id, object $data) : void
    {
        $this->request(
            $this->external_api_data_storage_config->store_data_url,
            $scorm_id,
            $user_id,
            DefaultMethod::POST,
            $data
        );
    }


    private function request(string $url, string $scorm_id, ?string $user_id = null, ?Method $method = null, ?object $data = null) : ?object
    {
        $placeholders = [
            "scorm_id" => $scorm_id
        ];
        if ($user_id !== null) {
            $placeholders["user_id"] = $user_id;
        }

        $headers = [];

        if ($data !== null) {
            $data = JsonBodyDto::new(
                $data
            );
        }

        $method ??= DefaultMethod::GET;
        $return = $method === DefaultMethod::GET;
        if ($return) {
            $headers[DefaultHeaderKey::ACCEPT->value] = DefaultBodyType::JSON->value;
        }

        $response = $this->rest_api->makeRequest(
            ClientRequestDto::new(
                preg_replace_callback("/{([a-z_]+)}/", fn(array $matches) : string => $placeholders[$matches[1]] ?? "", $url),
                $method,
                null,
                null,
                $headers,
                $data,
                null,
                null,
                $return,
                true,
                false,
                false
            )
        );

        if (!$return || empty($data = $response?->raw_body) || empty($data = json_decode($data))) {
            $data = null;
        }

        return $data;
    }
}
