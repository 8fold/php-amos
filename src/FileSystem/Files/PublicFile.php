<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

final class PublicFile implements Falsifiable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(
        Root $root,
        string $filename,
        string $at = ''
    ): self {
        return self::inPublicRoot($root->publicRoot(), $filename, $at);
    }

    public static function inPublicRoot(
        PublicRoot $root,
        string $filename,
        string $at = ''
    ): self {
        if (str_starts_with($filename, '/') === false) {
            $filename = '/' . $filename;
        }

        if (str_ends_with($at, '/')) {
            $at = substr($at, 0, -1);
        }

        return new self($root, $filename, $at);
    }

    private function __construct(
        private readonly PublicRoot $root,
        private readonly string $filename,
        private readonly string $at = ''
    ) {
        $this->fileInfo = new SplFileInfo($root->toString() . $at . $filename);
    }

    public function notFound(): bool
    {
        return ! $this->toBool();
    }

    public function isFile(): bool
    {
        return $this->fileInfo->isFile();
    }

    public function toBool(): bool
    {
        return $this->isFile();
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
