<?php
declare(strict_types=1);

namespace Eightfold\Amos\FileSystem;

use Stringable;

use Psr\Http\Message\UriInterface;

class Path implements Stringable
{
    static public function fromUri(UriInterface $uri): self
    {
        return self::fromString(
            str_replace('/', DIRECTORY_SEPARATOR, $uri->getPath())
        );
    }

    static public function fromString(string $path = ''): self
    {
        if ($path === '') {
            $path = DIRECTORY_SEPARATOR;
        }

        if (str_starts_with($path, DIRECTORY_SEPARATOR) === false) {
            $path = DIRECTORY_SEPARATOR . $path;
        }

        if (
            $path !== DIRECTORY_SEPARATOR and
            str_ends_with($path, DIRECTORY_SEPARATOR)
        ) {
            $path = substr($path, 0, -1);
        }
        return new self($path);
    }

    final private function __construct(readonly private string $path)
    {
    }

    public function parts(): array
    {
        return array_filter(
            explode(DIRECTORY_SEPARATOR, $this->path)
        );
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
