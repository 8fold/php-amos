<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PrivateDirectory;

final class PrivateFile implements Findable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(
        Root $root,
        string|Filename $filename, // TODO: Create filename class
        string|Path $at = ''
    ): self {
        if (is_string($at)) {
            $at = Path::fromString($at);
        }
        return self::inPrivateDirectory(
            PrivateDirectory::inRoot($root, $at),
            $filename
        );
    }

    public static function inPrivateDirectory(
        PrivateDirectory $directory,
        string|Filename $filename
    ): self {
        if (is_string($filename)) {
            $filename = Filename::fromString($filename);
        }
        return new self($directory, $filename);
    }

    private function __construct(
        private readonly PrivateDirectory $directory, // @phpstan-ignore-line
        private readonly Filename $filename // @phpstan-ignore-line
    ) {
        $this->fileInfo = new SplFileInfo(
            $directory->toString() .
            $filename->toString()
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
