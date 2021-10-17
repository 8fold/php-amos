<?php

namespace Eightfold\Amos\Forms\FormControls;

interface FormControlInterface
{
    public function type(string $type = ''): string;

    public function value(string $value = '');

    public function optional(bool $optional = true);

    public function errorMessage(string $message = '');
}
