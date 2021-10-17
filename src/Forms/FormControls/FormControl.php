<?php

declare(strict_types=1);

namespace Eightfold\Amos\Forms\FormControls;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\HTMLBuilder\Element as HtmlElement;

abstract class FormControl implements Buildable
{
    protected $type = '';

    protected $required = true;

    protected $label = '';
    protected $name = '';
    protected $value = '';

    protected $errorMessage = '';

    public function optional(bool $optional = true)
    {
        $this->required = ! $optional;
        return $this;
    }

    // public function type(string $type = ""): string
    // {
    //     $this->type = $type;
    //     return $this;
    // }

    // public function value(string $value = "")
    // {
    //     $this->value = $value;
    //     return $this;
    // }

    public function errorMessage(string $message = "")
    {
        $this->errorMessage = $message;
        return $this;
    }

    protected function label()
    {
        return HtmlElement::label($this->label)
            ->props("id {$this->name}-label", "for {$this->name}");
    }

    protected function error()
    {
        if (strlen($this->errorMessage) === 0) {
            return '';
        }

        return PHPUIKit::span($this->errorMessage)->attr(
            "is form-control-error-message",
            "id {$this->name}-error-message",
        );
    }

    public function __toString(): string
    {
        return $this->build();
    }

    abstract public function build(): string;
}
