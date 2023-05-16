<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Psr\Log\LoggerInterface;

use Eightfold\Amos\RealPaths\FromPrimitives as RealPathsFromPrimitives;

use Eightfold\Amos\Logger\Log;

final class FromPrimitives
{
    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): string {
        return self::inFile(
            RealPathsFromPrimitives::forPublicDir($contentRoot, $at, $logger),
            $filename,
            '',
            $logger
        );
    }

    public static function inFile(
		string|Stringable $contentRoot,
		string|Stringable $filename,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): string {
        $real_path = RealPathsFromPrimitives::forFile(
            $contentRoot,
            $filename,
            $at,
            $logger
        );

        if (strlen($real_path) === 0) {
            return '';
        }

        $contents = file_get_contents($real_path);
        if ($contents === false) {
            return '';
        }
        return $contents;
    }

	public static function logCouldNotGetContent(
		string $path,
		LoggerInterface $logger
	): void {
		$logger->error(
			Log::message('Could not get content for: {path}', ['path' => $path])
		);
	}
}
