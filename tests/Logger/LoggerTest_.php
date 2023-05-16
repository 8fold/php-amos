<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Logger;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\Logger\Logger;
use Eightfold\Amos\Logger\Log;

use StdClass;

class LoggerTest extends TestCase
{
    private function deleteDir(string $path): void
    {
        if (is_dir($path)) {
            $dirContent = scandir($path);
            foreach ($dirContent as $dc) {
                if ($dc === '.' or $dc === '..') {
                    continue;
                }

                $fullPath = $path . '/' . $dc;
                if (is_dir($fullPath)) {
                    $this->deleteDir($fullPath);

                } else {
                    unlink($fullPath);

                }
            }
            rmdir($path);
        }
    }

    private function filesInFolder(string $folderPath): array
    {
        $contents = scandir($folderPath);
        $logs = [];
        foreach ($contents as $c) {
            if ($c === '.' or $c === '..') {
                continue;
            }
            $logs[] = $c;
        }
        return $logs;
    }

    public function tearDown(): void
    {
        $logsPath = __DIR__ . '/../test-content/logs';
        $this->deleteDir($logsPath);
    }

    /**
     * @test
     */
    public function did_save_logs(): void
    {
        $logsRoot  = __DIR__ . '/../test-content/logs';
        $infoRoot  = __DIR__ . '/../test-content/logs/info';
        $errorRoot = __DIR__ . '/../test-content/logs/error';

        $expected = 'This is an info message.';

        Logger::init($logsRoot)->info(
            Log::with(
                'This is an {level} {type}.',
                context: [
                    'level' => 'info',
                    'type'  => 'message'
                ]
            )
        );

        $this->assertTrue(
            is_dir($infoRoot)
        );


        $this->assertTrue(
            count($this->filesInFolder($infoRoot)) === 1
        );

        Logger::init($logsRoot)->error(
            Log::with(
                'This is an {level} {type}.',
                context: [
                    'level' => 'error',
                    'type'  => 'message'
                ]
            )
        );

        $this->assertTrue(
            is_dir($errorRoot)
        );


        $this->assertTrue(
            count($this->filesInFolder($errorRoot)) === 1
        );
    }
}
