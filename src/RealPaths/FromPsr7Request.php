<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Stingable;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\Logger\Log;

use Eightfold\Amos\RealPaths\FromPsr7Uri;

final class FromPsr7Request
{
	/**
	 * @return string[]
	 */
	public static function forPublicFilesNamed(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		RequestInterface $request,
		LoggerInterface|false $logger = false
	): array {
		return FromPsr7Uri::forPublicFilesNamed(
			$contentRoot,
			$filename,
			$request->getUri(),
			$logger
		);
	}

	public static function forPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		RequestInterface $request,
		LoggerInterface|false $logger = false
	): string {
		return FromPsr7Uri::forPublicFile(
			$contentRoot,
			$filename,
			$request->getUri(),
			$logger
		);
	}

	public static function forPublicDir(
		string|Stringable $contentRoot,
		RequestInterface $request,
		LoggerInterface|false $logger = false
	): string {
		return FromPsr7Uri::forPublicDir(
			$contentRoot,
			$request->getUri(),
			$logger
		);
	}
}
