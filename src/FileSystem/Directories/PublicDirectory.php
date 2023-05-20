<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Directories;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

final class PublicDirectory implements Findable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(Root $root, string $at = ''): self
    {
        return self::inPublicRoot($root->publicRoot(), $at);
    }

    public static function inPublicRoot(PublicRoot $root, string $at = ''): self
    {
        if (str_starts_with($at, '/') === false) {
            $at = '/' . $at;
        }
        return new self($root, $at);
    }

    private function __construct(
        private readonly PublicRoot $root, // @phpstan-ignore-line
        private readonly string $at = '' // @phpstan-ignore-line
    ) {
        $this->fileInfo = new SplFileInfo($root . $at);
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
