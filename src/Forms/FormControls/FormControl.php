<?php

namespace Eightfold\LaravelMarkup\Elements\FormControls;

use Eightfold\Markup\Html\HtmlElement;

use Eightfold\Shoop\Shoop;
use Eightfold\Markup\UIKit as PHPUIKit;

abstract class FormControl extends HtmlElement implements FormControlInterface
{
    protected $type = "";

    protected $required = true;

    protected $label = "Select";
    protected $name = "select";
    protected $value = "";

    protected $errorMessage = "";

    public function optional(bool $optional = true)
    {
        $this->required = ! $optional;
        return $this;
    }

    public function type(string $type = ""): string
    {
        $this->type = $type;
        return $this;
    }

    public function value(string $value = "")
    {
        $this->value = $value;
        return $this;
    }

    public function errorMessage(string $message = "")
    {
        $this->errorMessage = $message;
        return $this;
    }

    public function label()
    {
        return PHPUIKit::label($this->label)
            ->attr("id {$this->name}-label", "for {$this->name}");
    }

    protected function error()
    {
        if (Shoop::this($this->errorMessage)->efIsEmpty()) {
            return "";
        }
        return PHPUIKit::span($this->errorMessage)->attr(
            "is form-control-error-message",
            "id {$this->name}-error-message",
        );
    }
}
