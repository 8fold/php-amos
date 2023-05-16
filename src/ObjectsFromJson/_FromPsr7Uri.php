<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use Stingable;
use StdClass;

use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\ObjectsFromJson\FromPrimitives;

final class FromPsr7Uri
{
    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		UriInterface $uri,
		LoggerInterface|false $logger = false
    ): StdClass {
        return FromPrimitives::inPublicFile(
            $contentRoot,
            $filename,
            $uri->getPath(),
            $logger
        );
    }
}
