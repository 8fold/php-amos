<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem;

use Stringable;

/**
 * Rename to PathFromRoot
 */
class PathFromRoot implements Stringable
{
    static public function fromString(string $path = ''): self
    {
        if ($path === '') {
            $path = '/';
        }

        if (str_starts_with($path, '/') === false) {
            $path = '/' . $path;
        }

        if ($path !== '/' and str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }
        return new self($path);
    }

    final private function __construct(readonly private string $path)
    {
    }

    public function toString(): string
    {
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
