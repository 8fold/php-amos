<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use Stingable;
use StdClass;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\ObjectsFromJson\FromPsr7Uri;

final class FromPsr7Request
{
    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		RequestInterface $request,
		LoggerInterface|false $logger = false
    ): StdClass {
        return FromPsr7Uri::inPublicFile(
            $contentRoot,
            $filename,
            $request->getUri(),
            $logger
        );
    }
}
