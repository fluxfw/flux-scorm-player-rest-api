<?php

namespace FluxScormPlayerRestApi\Adapter\MetadataStorage;

interface MetadataStorage
{

    public function deleteMetadata(string $scorm_id) : void;


    public function getMetadata(string $scorm_id) : ?MetadataDto;


    public function storeMetadata(string $scorm_id, MetadataDto $metadata) : void;
}
