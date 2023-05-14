<?php
declare(strict_types=1);

namespace Eightfold\Amos\Php\Primitives;

use Stringable as BaseStringable;

use Eightfold\Amos\Php\Interfaces\Stringable;

class StringType implements Stringable
{
    public static function fromString(string|Stringable|BaseStringable $string): self
    {
        $string = (string) $string;
        return new self($string);
    }

    final private function __construct(private readonly string $string)
    {
    }

    public function toString(): string
    {
        return $this->string;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
