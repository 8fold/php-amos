<?php
declare(strict_types=1);

namespace Eightfold\Amos\Logger;

use Psr\Log\AbstractLogger;

use StdClass;
use Stringable;

use Ramsey\Uuid\Uuid;

class Logger extends AbstractLogger
{
    public static function init(string $logsRoot): self
    {
        return new self($logsRoot);
    }

    final private function __construct(private string $logsRoot)
    {
    }

    private function shopRoot(): string
    {
        return $this->logsRoot;
    }

    private function filePath(string $level): string
    {
        $filename = date('Ymd') . '-' . Uuid::uuid7()->toString() . '.json';
        return $this->shopRoot() . '/' . $level . '/' . $filename;
    }

    public function log(
        $level,
        string|\Stringable $message,
        array $context = []
    ): void {
        $dirPath = $this->shopRoot() . '/' . $level;
        if (is_dir($dirPath)) {
            file_put_contents(
                $this->filePath($level),
                (string) $message
            );

        } elseif (mkdir($dirPath, recursive: true)) {
            $this->log($level, $message, $context);

        }
    }
}
