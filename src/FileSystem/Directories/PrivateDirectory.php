<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Directories;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\Root;

final class PrivateDirectory implements Falsifiable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(Root $root, string $at = ''): self
    {
        if (str_starts_with($at, '/') === false) {
            $at = '/' . $at;
        }
        return new self($root, $at);
    }

    private function __construct(
        private readonly Root $root, // @phpstan-ignore-line
        private readonly string $at = '' // @phpstan-ignore-line
    ) {
        $this->fileInfo = new SplFileInfo($root . $at);
    }

    public function notFound(): bool
    {
        return ! $this->toBool();
    }

    public function isDir(): bool
    {
        return $this->fileInfo->isDir();
    }

    public function toBool(): bool
    {
        return $this->isDir();
    }

    public function toString(): string
    {
        if ($this->notFound()) {
            return '';
        }
        return $this->fileInfo->getRealPath();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
