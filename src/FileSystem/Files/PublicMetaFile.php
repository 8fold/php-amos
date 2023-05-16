<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\FileSystem\Files\PublicFile;

final class PublicMetaFile implements Falsifiable, Stringable
{
    private const FILENAME = 'meta.json';

    private readonly SplFileInfo $fileInfo;

    public static function inRoot(Root $root, string $at = ''): self
    {
        return self::inPublicRoot($root->publicRoot(), $at);
    }

    public static function inPublicRoot(PublicRoot $root, string $at = ''): self
    {
        if (str_ends_with($at, '/')) {
            $at = substr($at, 0, -1);
        }

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
        return $this->publicFile->notFound();
    }

    public function isFile(): bool
    {
        return $this->publicFile->isFile();
    }

    public function toBool(): bool
    {
        return $this->publicFile->toBool();
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
