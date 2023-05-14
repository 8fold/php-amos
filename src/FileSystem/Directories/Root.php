<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Directories;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\PublicRoot;

final class Root implements Falsifiable, Stringable
{
    public static function fromString(string $path): self
    {
        return self::fromSplFileInfo(
            new SplFileInfo($path)
        );
    }

    public static function fromSplFileInfo(SplFileInfo $fileInfo): self
    {
        return new self($fileInfo);
    }

    private function __construct(private readonly SplFileInfo $fileInfo)
    {
    }

    public function publicRoot(): PublicRoot
    {
        return PublicRoot::inRoot($this);
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
