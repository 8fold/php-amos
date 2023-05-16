<?php
declare(strict_types=1);

namespace Eightfold\Amos\Php\Interfaces;

use Stringable as BaseStringable;

interface Stringable extends BaseStringable
{
    public function toString(): string;
}
