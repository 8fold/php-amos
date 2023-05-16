<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Stringable;

use Psr\Log\LoggerInterface;

use Eightfold\Amos\RealPaths\FromPrimitives;

trait ForFilesTrait
{
    /**
     * @return string[]
     */
    public static function inPublic(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): array {
        return self::in(
            FromPrimitives::forPublicDir($contentRoot, $at, $logger),
            '',
            $logger
        );
    }

    public static function forPublicFile(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): string {
        return self::forFile(
            FromPrimitives::forPublicDir($contentRoot, $at, $logger),
            '',
            $logger
        );
    }

    /**
     * @return string[]
     */
    public static function in(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): array {
        return FromPrimitives::forFilesNamed(
            $contentRoot,
            self::filename(),
            $at,
            $logger
        );
    }

    public static function forFile(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): string {
        return FromPrimitives::forFile(
            $contentRoot,
            self::filename(),
            $at,
            $logger
        );
    }
}
