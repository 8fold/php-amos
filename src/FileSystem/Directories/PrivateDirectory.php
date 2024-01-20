<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Directories;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Path;

use Eightfold\Amos\FileSystem\Directories\Root;

final class PrivateDirectory implements Findable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(
        Root $root,
        Path $at
    ): self {
        return new self($root, $at);
    }

    // TODO: mark as final
    private function __construct(
        private readonly Root $root, // @phpstan-ignore-line
        private readonly Path $at // @phpstan-ignore-line
    ) {
        $this->fileInfo = new SplFileInfo($root . $at->toString());
    }

    public function notFound(): bool
    {
        return ! $this->toBool();
    }

    public function found(): bool
    {
        return $this->toBool();
    }

    public function exists(): bool
    {
        return $this->toBool();
    }

    public function nonexistent(): bool
    {
        return ! $this->toBool();
    }

    public function toBool(): bool
    {
        return $this->isDir();
    }

    public function isDir(): bool
    {
        return $this->fileInfo->isDir();
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
