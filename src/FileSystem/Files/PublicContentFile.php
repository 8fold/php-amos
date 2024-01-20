<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Path;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\FileSystem\Files\PublicFile;

final class PublicContentFile implements Findable, Stringable
{
    private const FILENAME = 'content.md';

    public static function inRoot(Root $root, Path $at): self
    {
        return self::inPublicRoot($root->publicRoot(), $at);
    }

    public static function inPublicRoot(PublicRoot $root, Path $at): self
    {
        return new self(
            PublicFile::inPublicRoot($root, self::FILENAME, $at)
        );
    }

    private function __construct(
        private readonly PublicFile $publicFile
    ) {
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
        return $this->publicFile->toBool();
    }

    public function isFile(): bool
    {
        return $this->publicFile->isFile();
    }

    public function toString(): string
    {
        if ($this->notFound()) {
            return '';
        }
        return $this->publicFile->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
