<?php

namespace Eightfold\Amos\Forms\FormControls;

use Eightfold\HTMLBuilder\Element as HtmlElement;
// TODO: Not tested or used
class Password extends FormControl
{
    public static function create(string $label, string $name): Password
    {
        return new Password($label, $name);
    }

    public function __construct(string $label, string $name)
    {
        $this->type  = "password";
        $this->label = $label;
        $this->name  = $name;
    }

    public function input(): HtmlElement
    {
        $props = [
            "id {$this->name}",
            "name {$this->name}",
            "type {$this->type}",
            "aria-describedby {$this->name}-label"
        ];

        $control = HtmlElement::input()->omitEndTag()->props(...$props);
        if ($this->required) {
            $control = $control->props('required required');
        }
        return $control;
    }

    public function build(): string
    {
        $control = HtmlElement::div(
            $this->label(),
            $this->error(),
            $this->input()
        )->props('is form-control');

        if (strlen($this->errorMessage) > 0) {
            $control = $control->props('is form-control-with-errors');
        }

        return $control->build();
    }
}
