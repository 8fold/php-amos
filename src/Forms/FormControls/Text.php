<?php

declare(strict_types=1);

namespace Eightfold\Amos\Forms\FormControls;

use Eightfold\HTMLBuilder\Element as HtmlElement;

class Text extends FormControl
{
    // private string $placeholder = '';
    private int $maxlength = 254;

    private bool $hasCounter = false;

    public static function create(
        string $label,
        string $name,
        string $value = ''
    ): Text {
        return new Text($label, $name, $value);
    }

    public function __construct(
        string $label,
        string $name,
        string $value = ''
    ) {
        $this->type = 'text';
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
    }

    public function email(): Text
    {
        $this->type = "email";
        return $this;
    }

    public function long(): Text
    {
        $this->type = "textarea";
        return $this;
    }

    public function hasCounter(): Text
    {
        $this->hasCounter = true;
        return $this;
    }

    // public function placeholder(string $placeholder = "")
    // {
    //     if (Shoop::this($placeholder)->isEmpty()->reversed()->unfold()) {
    //         $this->placeholder = $placeholder;
    //     }
    //     return $this;
    // }

    public function maxlength(int $maxlength = 0): Text
    {
        if ($maxlength > 0) {
            $this->maxlength = $maxlength;
        }
        return $this;
    }

    private function input(): HtmlElement
    {
        $props = [
            "id {$this->name}",
            "name {$this->name}",
            "aria-describedby {$this->name}-label"
        ];

        // if (strlen($this->placeholder) > 0) {
        //     $props[] = "placeholder {$this->placeholder}";
        // }

        if ($this->maxlength > 0) {
            $props[] = "maxlength {$this->maxlength}";
        }

        if ($this->required) {
            $props[] = 'required required';
        }

        if ($this->type === "textarea") {
            return HtmlElement::textarea($this->value)->props(...$props);

        }

        $props[] = (
            is_string($this->value) and
            strlen($this->value) > 0
        ) ? "value {$this->value}" : '';
        $props[] = "type {$this->type}";
        return HtmlElement::input()->omitEndTag()->props(...$props);
    }

    /**
     * @return HtmlElement|string [description]
     */
    private function counter()
    {
        if (! $this->hasCounter) {
            return '';
        }

        return HtmlElement::span(
            HtmlElement::i("{$this->maxlength}"),
            ' characters remaining'
        )->props("id {$this->name}-counter", 'aria-live polite');
    }

    public function build(): string
    {
        $control = HtmlElement::div(
            $this->label(),
            $this->error(),
            $this->input(),
            $this->counter()
        )->props('is form-control');

        if (strlen($this->errorMessage) > 0) {
            $control = $control->props('is form-control-with-errors');
        }

        return $control->build();
    }
}
