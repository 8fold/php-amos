<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use Stingable;
use StdClass;

use Psr\Log\LoggerInterface;

use Eightfold\Amos\Logger\Log;

use Eightfold\Amos\PlainText\FromPrimitives as PlainTextFromPrimitives;
use Eightfold\Amos\RealPaths\FromPrimitives as RealPathsFromPrimitives;

final class FromPrimitives
{
    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): StdClass {
        $json = PlainTextFromPrimitives::inPublicFile(
            $contentRoot,
            $filename,
            $at,
            $logger
        );
        return self::fromString($json, $logger);
    }

    public static function inFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): StdClass {
        $json = PlainTextFromPrimitives::inFile(
            $contentRoot,
            $filename,
            $at,
            $logger
        );
        return self::fromString($json, $logger);
    }

    public static function fromString(
        string $json,
        LoggerInterface|false $logger = false
    ): StdClass {
        $obj = json_decode($json);
        if (is_object($obj) === false or is_a($obj, StdClass::class) === false) {
            if ($logger !== false and is_a($logger, LoggerInterface::class)) {
                self::couldNotDecodeJson(
                    RealPathsFromPrimitives::forFile(
                        $contentRoot,
                        $filename,
                        $at,
                        $logger
                    ),
                    $logger
                );
            }
            return new StdClass();
        }
        return $obj;
    }

    private static function couldNotDecodeJson(
		string $path,
		LoggerInterface $logger
    ): void {
		$logger->error(
			Log::message('Not decoded: {path}', ['path' => $path])
		);
    }
}
