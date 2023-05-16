<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Stingable;

use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\Logger\Log;

use Eightfold\Amos\RealPaths\FromPrimitives;

final class FromPsr7Uri
{
	/**
	 * @return string[]
	 */
	public static function forPublicFilesNamed(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		UriInterface $uri,
		LoggerInterface|false $logger = false
	): array {
		return FromPrimitives::forPublicFilesNamed(
			$contentRoot,
			$filename,
			$uri->getPath(),
			$logger
		);
	}

	public static function forPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		UriInterface $uri,
		LoggerInterface|false $logger = false
	): string {
		return FromPrimitives::forPublicFile(
			$contentRoot,
			$filename,
			$uri->getPath(),
			$logger
		);
	}

	public static function forPublicDir(
		string|Stringable $contentRoot,
		UriInterface $uri,
		LoggerInterface|false $logger = false
	): string {
		return FromPrimitives::forPublicDir(
			$contentRoot,
			$uri->getPath(),
			$logger
		);
	}
}
