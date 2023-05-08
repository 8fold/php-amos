<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use Stingable;
use StdClass;

use Psr\Log\LoggerInterface;

use Eightfold\Amos\Logger\Log;

use Eightfold\Amos\SiteInterface;
use Eightfold\Amos\ObjectsFromJson\FromPsr7Request;
use Eightfold\Amos\ObjectsFromJson\FromPrimitives;

final class FromSite
{
    public static function inPublicFile(
        SiteInterface $site,
		string|Stringable $filename,
		LoggerInterface|false $logger = false
    ): StdClass {
        return FromPsr7Request::inPublicFile(
            $site->contentRoot(),
            $filename,
            $site->request(),
            $site->logger()
        );
    }

    public static function inFile(
        SiteInterface $site,
		string|Stringable $filename,
        string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): StdClass {
        return FromPrimitives::inFile(
            $site->contentRoot(),
            $filename,
            $at,
            $site->logger()
        );
    }
}
