<?php

declare(strict_types=1);

namespace Eightfold\Amos\Forms\FormControls;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\HTMLBuilder\Element as HtmlElement;

abstract class FormControl implements Buildable
{
    protected string $type = '';

    protected bool $required = true;

    protected string $label = '';

    protected string $name = '';

    /**
     * @var string|array<string|HtmlElement>
     */
    protected $value = '';

    protected string $errorMessage = '';

    /**
     * @return static                 [description]
     */
    public function optional(bool $optional = true)
    {
        $this->required = ! $optional;
        return $this;
    }

    // public function type(string $type = ''): string
    // {
    //     $this->type = $type;
    //     return $this;
    // }

    // public function value(string $value = '')
    // {
    //     $this->value = $value;
    //     return $this;
    // }

    /**
     * @return static                 [description]
     */
    public function errorMessage(string $message = '')
    {
        $this->errorMessage = $message;
        return $this;
    }

    protected function label(): HtmlElement
    {
        return HtmlElement::label($this->label)
            ->props("id {$this->name}-label", "for {$this->name}");
    }

    /**
     * @return string|HtmlElement [description]
     */
    protected function error()
    {
        if (strlen($this->errorMessage) === 0) {
            return '';
        }

        return HtmlElement::span($this->errorMessage)->props(
            'is form-control-error-message',
            "id {$this->name}-error-message",
        );
    }

    public function __toString(): string
    {
        return $this->build();
    }

    abstract public function build(): string;
}
