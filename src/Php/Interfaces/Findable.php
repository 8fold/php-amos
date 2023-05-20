<?php
declare(strict_types=1);

namespace Eightfold\Amos\Php\Interfaces;

use Eightfold\Amos\Php\Interfaces\Falsifiable;

interface Findable extends Falsifiable
{
    public function found(): bool;

    public function notFound(): bool;

    public function exists(): bool;

    public function nonexistent(): bool;
}
