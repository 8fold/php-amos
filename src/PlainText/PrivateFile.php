<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Files\PrivateFile as FileSystemPrivateFile;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

final class PrivateFile
{
    public static function inRoot(
        Root $root,
        string $filename,
        string $at = ''
    ): self {
        return new self(
            FileSystemPrivateFile::inRoot($root, $filename, $at)
        );
    }

    final private function __construct(
        private readonly FileSystemPrivateFile $privateFile
    ) {
    }

    public function notFound(): bool
    {
        return $this->privateFile->notFound();
    }

    public function isFile(): bool
    {
        return $this->privateFile->isFile();
    }

    public function toBool(): bool
    {
        return $this->privateFile->toBool();
    }

    public function toString(): string
    {
        if ($this->notFound()) {
            return '';
        }

        $content = file_get_contents($this->privateFile->toString());
        if ($content === false) {
            return '';
        }
        return $content;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
