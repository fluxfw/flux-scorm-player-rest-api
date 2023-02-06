<?php

namespace FluxScormPlayerRestApi\Service\PlayScormPackage\Command;

use FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxScormPlayerRestApi\Service\Filesystem\Port\FilesystemService;

class PlayScormPackageCommand
{

    private function __construct(
        private readonly FilesystemService $filesystem_service
    ) {

    }


    public static function new(
        FilesystemService $filesystem_service
    ) : static {
        return new static(
            $filesystem_service
        );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        $metadata = $this->filesystem_service->getScormPackageMetadata(
            $id
        );

        if ($metadata === null) {
            return null;
        }

        $config = [
            "type"         => $metadata->type,
            "api_settings" => [
                "autocommit"            => true,
                "autocommitSeconds"     => 30,
                "lmsCommitUrl"          => "data/" . $id . "/" . $user_id,
                "dataCommitFormat"      => "json",
                "commitRequestDataType" => DefaultBodyType::JSON->value,
                "autoProgress"          => false,
                "logLevel"              => 1,
                "mastery_override"      => false,
                "selfReportSessionTime" => false,
                "alwaysSendTotalTime"   => false
            ]
        ];

        $html = file_get_contents(__DIR__ . "/template/index.html");

        $placeholders = [
            "config"     => base64_encode(json_encode($config, JSON_UNESCAPED_SLASHES)),
            "entrypoint" => $metadata->entrypoint,
            "id"         => $id,
            "title"      => $metadata->title
        ];

        return preg_replace_callback("/{([a-z_]+)}/", fn(array $matches) : string => htmlspecialchars($placeholders[$matches[1]]), $html);
    }
}
