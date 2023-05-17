<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Files\PublicFile as FileSystemPublicFile;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

final class PublicFile
{
    public static function inRoot(
        Root $root,
        string $filename,
        string $at = ''
    ): self {
        return new self(
            FileSystemPublicFile::inRoot($root, $filename, $at)
        );
    }

    final private function __construct(
        private readonly FileSystemPublicFile $publicFile
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

        $content = file_get_contents($this->publicFile->toString());
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
