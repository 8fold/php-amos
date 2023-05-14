<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Directories;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\Root;

final class PublicRoot implements Falsifiable, Stringable
{
    private const FOLDER_NAME = '/public';

    public static function inRoot(Root $root): self
    {
        return self::fromSplFileInfo(
            new SplFileInfo($root->toString() . self::FOLDER_NAME)
        );
    }

    public static function fromSplFileInfo(SplFileInfo $fileInfo): self
    {
        return new self($fileInfo);
    }

    private function __construct(private readonly SplFileInfo $fileInfo)
    {
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
