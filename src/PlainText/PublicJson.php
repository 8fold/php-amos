<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Files\PublicFile;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

final class PublicJson implements Findable, Stringable
{
    public static function inRoot(
        Root $root,
        string|Filename $filename,
        Path $at
    ): self {
        return new self(
            PublicFile::inRoot($root, $filename, $at)
        );
    }

    final private function __construct(
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
