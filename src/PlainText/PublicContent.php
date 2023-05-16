<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Files\PublicContentFile;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

final class PublicContent
{
    public static function inRoot(Root $root, string $at = ''): self
    {
        return self::fromPublicContentFile(
            PublicContentFile::inRoot($root, $at)
        );
    }

    public static function fromPublicContentFile(
        PublicContentFile $publicFile
    ): self {
        return new self($publicFile);
    }

    final private function __construct(
        private readonly PublicContentFile $publicFile
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
