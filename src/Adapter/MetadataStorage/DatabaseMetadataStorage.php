<?php

namespace FluxScormPlayerRestApi\Adapter\MetadataStorage;

use MongoDB\Collection;
use MongoDB\Database;

class DatabaseMetadataStorage implements MetadataStorage
{

    private function __construct(
        private readonly Collection $collection
    ) {

    }


    public static function new(
        Collection $collection
    ) : static {
        return new static(
            $collection
        );
    }


    public static function newFromDatabase(
        Database $database
    ) : static {
        return static::new(
            $database->selectCollection("metadata")
        );
    }


    public function deleteMetadata(string $scorm_id) : void
    {
        $this->collection->deleteMany([
            "scorm_id" => $scorm_id
        ]);
    }


    public function getMetadata(string $scorm_id) : ?MetadataDto
    {
        $document = $this->collection->findOne([
            "scorm_id" => $scorm_id
        ]);

        if ($document === null) {
            return null;
        }

        return MetadataDto::new(
            $document["title"],
            $document["entrypoint"],
            MetadataType::from($document["type"])
        );
    }


    public function storeMetadata(string $scorm_id, MetadataDto $metadata) : void
    {
        $this->collection->replaceOne([
            "scorm_id" => $scorm_id
        ], [
            "scorm_id"   => $scorm_id,
            "title"      => $metadata->title,
            "entrypoint" => $metadata->entrypoint,
            "type"       => $metadata->type->value
        ], ["upsert" => true]);
    }
}
