<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Files\PrivateFile;

use Eightfold\Amos\Php\Interfaces\Findable;
use Eightfold\Amos\Php\Interfaces\Stringable;

final class PrivateJson implements Findable, Stringable
{
    public static function inRoot(
        Root $root,
        string $filename,
        string $at = ''
    ): self {
        return new self(
            PrivateFile::inRoot($root, $filename, $at)
        );
    }

    final private function __construct(
        private readonly PrivateFile $privateFile
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
        return $this->privateFile->toBool();
    }

    public function isFile(): bool
    {
        return $this->privateFile->isFile();
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
