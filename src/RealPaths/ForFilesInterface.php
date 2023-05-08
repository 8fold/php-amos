<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Stringable;

use Psr\Log\LoggerInterface;

interface ForFilesInterface
{
    public static function filename(): string;

    /**
     * @return string[]
     */
    public static function inPublic(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): array;

    public static function forPublicFile(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): string;

    /**
     * @return string[]
     */
    public static function in(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): array;

    public static function forFile(
        string|Stringable $contentRoot,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): string;
}
