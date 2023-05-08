<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use Stingable;
use StdClass;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\SiteInterface;
use Eightfold\Amos\RealPaths\ForMetadataFiles;
use Eightfold\Amos\ObjectsFromJson\FromPrimitives;

final class Meta
{
    public static function inPublicFromSite(
        SiteInterface $site,
        LoggerInterface|false $logger = false
    ): StdClass {
        return self::inPublicFromRequest(
            $site->contentRoot(),
            $site->request(),
            $logger
        );
    }

    public static function inPublicFromRequest(
        string|Stringable $contentRoot,
        RequestInterface $request,
        LoggerInterface|false $logger = false
    ): StdClass {
        return self::inPublicFromUri(
            $contentRoot,
            $request->getUri(),
            $logger
        );
    }

    public static function inPublicFromUri(
        string|Stringable $contentRoot,
        UriInterface $uri,
        LoggerInterface|false $logger = false
    ): StdClass {
        return self::inPublicFile(
            $contentRoot,
            $uri->getPath(),
            $logger
        );
    }

    public static function fromSite(
        SiteInterface $site,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): StdClass {
        return self::inFile(
            $site->contentRoot(),
            $at,
            $logger
        );
    }

    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): StdClass {
        return FromPrimitives::inPublicFile(
            $contentRoot,
            ForMetadataFiles::filename(),
            $at,
            $logger
        );
    }

    public static function inFile(
		string|Stringable $contentRoot,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): StdClass {
        return FromPrimitives::inFile(
            $contentRoot,
            ForMetadataFiles::filename(),
            $at,
            $logger
        );
    }
}
