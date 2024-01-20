<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem;

use Stringable;

class Filename implements Stringable
{
    public static function fromString(string $filename = ''): self
    {
        if (empty($filename)) {
            return new self('');
        }

        if (str_contains($filename, '.') === false) {
            return new self('');
        }

        if (str_starts_with($filename, DIRECTORY_SEPARATOR) === false) {
            $filename = DIRECTORY_SEPARATOR . $filename;
        }

        return new self($filename);
    }

    final private function __construct(readonly private string $filename)
    {
    }

    public function toString(): string
    {
        return $this->filename;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
