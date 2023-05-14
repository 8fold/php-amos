<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem\Files;

use SplFileInfo;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PrivateDirectory;

final class PrivateFile implements Falsifiable, Stringable
{
    private readonly SplFileInfo $fileInfo;

    public static function inRoot(
        Root $root,
        string $filename,
        string $at = ''
    ): self {
        if (str_ends_with($at, '/')) {
            $at = substr($at, 0, -1);
        }

        return self::inPrivateDirectory(
            PrivateDirectory::inRoot($root, $at),
            $filename
        );
    }

    public static function inPrivateDirectory(
        PrivateDirectory $directory,
        string $filename
    ): self {
        if (str_starts_with($filename, '/') === false) {
            $filename = '/' . $filename;
        }

        return new self($directory, $filename);
    }

    public static function fromSplFileInfo(SplFileInfo $fileInfo): self
    {
        return new self($fileInfo);
    }

    private function __construct(
        private readonly PrivateDirectory $directory,
        private readonly string $filename
    ) {
        $this->fileInfo = new SplFileInfo($directory->toString() . $filename);
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
