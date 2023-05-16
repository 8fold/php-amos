<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Stingable;

use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\SiteInterface;

use Eightfold\Amos\Logger\Log;

use Eightfold\Amos\RealPaths\FromPrimitives;
use Eightfold\Amos\RealPaths\FromPsr7Request;

final class FromSite
{
	/**
	 * @return string[]
	 */
	public static function forPublicFilesNamed(
		SiteInterface $site,
		string|Stringable $filename,
	): array {
		return FromPsr7Request::forPublicFilesNamed(
			$site->contentRoot(),
			$filename,
			$site->request(),
			$site->logger()
		);
	}

	public static function forPublicFile(
		SiteInterface $site,
		string|Stringable $filename,
	): string {
		return FromPsr7Request::forPublicFile(
			$site->contentRoot(),
			$filename,
			$site->request(),
			$site->logger()
		);
	}

	public static function forPublicDir(
		SiteInterface $site
	): string {
		return FromPsr7Request::forPublicDir(
			$site->contentRoot(),
			$site->request(),
			$site->logger()
		);
	}

	/**
	 * @return string[]
	 */
	public static function forFilesNamed(
		SiteInterface $site,
		string|Stringable $filename,
		string|Stringable $at = ''
	): array {
        return FromPrimitives::forFilesNamed(
		    $site->contentRoot(),
		    $filename,
		    $at,
		    $site->logger()
	    );
	}

	public static function forFile(
		SiteInterface $site,
		string|Stringable $filename,
		string|Stringable $at = ''
	): string {
        return FromPrimitives::forFile(
		    $site->contentRoot(),
		    $filename,
		    $at,
		    $site->logger()
	    );
	}

	public static function forDir(
		SiteInterface $site,
		string|Stringable $at = ''
	): string {
        return FromPrimitives::forDir(
		    $site->contentRoot(),
		    $at,
		    $site->logger()
	    );
	}
}
