<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\PlainText\FromPrimitives;

final class FromPsr7Uri
{
    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		UriInterface $uri,
		LoggerInterface|false $logger = false
    ): string {
        return FromPrimitives::inPublicFile(
            $contentRoot,
            $filename,
            $uri->getPath(),
            $logger
        );
    }
}
