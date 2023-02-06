<?php

namespace FluxScormPlayerRestApi\Adapter\DataStorage;

use MongoDB\Collection;
use MongoDB\Database;

class DatabaseDataStorage implements DataStorage
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
            $database->selectCollection("data")
        );
    }


    public function deleteData(string $scorm_id) : void
    {
        $this->collection->deleteMany([
            "scorm_id" => $scorm_id
        ]);
    }


    public function getData(string $scorm_id, string $user_id) : ?object
    {
        return $this->collection->findOne([
                "scorm_id" => $scorm_id,
                "user_id"  => $user_id
            ])["data"] ?? (object) [];
    }


    public function storeData(string $scorm_id, string $user_id, object $data) : void
    {
        $this->collection->replaceOne([
            "scorm_id" => $scorm_id,
            "user_id"  => $user_id
        ], [
            "scorm_id" => $scorm_id,
            "user_id"  => $user_id,
            "data"     => $data
        ], ["upsert" => true]);
    }
}
