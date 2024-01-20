<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

final class PublicFile implements Findable, Stringable
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
        string $filename, // TODO: convert to class
        string $at = '' // TODO: use Path
    ): self {
        if (str_starts_with($filename, '/') === false) {
            $filename = '/' . $filename;
        }

        $at = Path::fromString($at)->toString();

        return new self($root, $filename, $at);
    }

    private function __construct(
        private readonly PublicRoot $root, // @phpstan-ignore-line
        private readonly string $filename, // @phpstan-ignore-line
        private readonly string $at = '' // @phpstan-ignore-line
    ) {
        $this->fileInfo = new SplFileInfo($root->toString() . $at . $filename);
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
