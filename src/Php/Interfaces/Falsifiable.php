<?php
declare(strict_types=1);

namespace Eightfold\Amos\Php\Interfaces;

interface Falsifiable
{
    public function toBool(): bool;
}
