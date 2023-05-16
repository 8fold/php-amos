<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Stingable;

use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\SiteInterface;

use Eightfold\Amos\Logger\Log;

use Eightfold\Amos\PlainText\FromPrimitives;
use Eightfold\Amos\PlainText\FromPsr7Request;

final class FromSite
{
	public static function inPublicFile(
		SiteInterface $site,
		string|Stringable $filename,
	): string {
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
		string|Stringable $at = ''
	): string {
        return FromPrimitives::inFile(
		    $site->contentRoot(),
		    $filename,
		    $at,
		    $site->logger()
	    );
	}
}
