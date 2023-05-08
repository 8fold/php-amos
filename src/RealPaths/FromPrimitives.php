<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Stingable;

use Psr\Log\LoggerInterface;

use Symfony\Component\Finder\Finder;

use Eightfold\Amos\Logger\Log;

final class FromPrimitives
{
	private const PUBLIC_DIRECTORY_NAME = '/public';

	/**
	 * @return string[]
	 */
	public static function forPublicFilesNamed(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
	): array {
		return self::forFilesNamed(
			self::forPublicDir($contentRoot, $at, $logger),
			$filename,
			'',
			$logger
		);
	}

	public static function forPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
	): string {
		return self::forFile(
			self::forPublicDir($contentRoot, $at, $logger),
			$filename,
			'',
			$logger
		);
	}

	public static function forPublicDir(
		string|Stringable $contentRoot,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
	): string {
		return self::forDir(
			$contentRoot . self::PUBLIC_DIRECTORY_NAME,
			$at,
			$logger
		);
	}

	/**
	 * @return string[]
	 */
	public static function forFilesNamed(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
	): array {
		$content_root = self::forDir($contentRoot, $at, $logger);
		if (strlen($content_root) === 0) {
			return [];
		}

		if (str_starts_with($filename, '/')) {
			$filename = substr($filename, 1);
		}

    	$iterator = (new Finder())->files()->name($filename)->in($content_root);

    	$array = iterator_to_array($iterator);

    	return array_keys($array);
	}

	public static function forFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
	): string {
		if (str_starts_with($filename, '/') === false) {
			$filename = '/' . $filename;
		}

		$proposed_dir = self::forDir($contentRoot, $at, $logger);
		$proposed_path = $proposed_dir . $filename;

		$real_path = realpath($proposed_path);
		if ($real_path === false) {
			if (is_bool($logger) === false) {
				self::logPathNotFound($proposed_path, $logger);
			}
        	return '';
		}
		return $real_path;
	}

	public static function forDir(
		string|Stringable $contentRoot,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
	): string {
		$content_root = (string) $contentRoot;
		$at           = (string) $at;

    	if (str_starts_with($at, '/') === false) {
        	$at = '/' . $at;
    	}

    	if ($at === '/') {
        	$at = '';
    	}

		$proposed_path = $content_root . $at;
    	$real_path = realpath($proposed_path);
    	if (
			$real_path === false or
			str_starts_with($real_path, $content_root) === false
		) {
			if (is_bool($logger) === false) {
				self::logPathNotFound($proposed_path, $logger);
			}
        	return '';
    	}
    	return $real_path;
	}

	public static function logPathNotFound(
		string $path,
		LoggerInterface $logger
	): void {
		$logger->error(
			Log::message('Not found: {path}', ['path' => $path])
		);
	}
}
