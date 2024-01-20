<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

final class PublicFile implements Findable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(
        Root $root,
        string|Filename $filename,
        Path $at
    ): self {
        return self::inPublicRoot($root->publicRoot(), $filename, $at);
    }

    public static function inPublicRoot(
        PublicRoot $root,
        string|Filename $filename, // TODO: convert to class
        Path $at
    ): self {
        if (is_string($filename)) {
            $filename = Filename::fromString($filename);
        }

        return new self($root, $filename, $at);
    }

    // TODO: mark as final
    private function __construct(
        private readonly PublicRoot $root, // @phpstan-ignore-line
        private readonly Filename $filename, // @phpstan-ignore-line
        private readonly Path $at // @phpstan-ignore-line
    ) {
        $this->fileInfo = new SplFileInfo(
            $root->toString() . $at->toString() . $filename->toString()
        );
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
        return $this->isFile();
    }

    public function isFile(): bool
    {
        return $this->fileInfo->isFile();
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
