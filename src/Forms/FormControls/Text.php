<?php

namespace Eightfold\LaravelMarkup\Elements\FormControls;

use Eightfold\Markup\UIKit as PHPUIKit;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\Shoop;

class Text extends FormControl
{
    private $placeholder = "";
    private $maxlength = 254;

    private $hasCounter = false;

    static public function fold(...$args): Foldable
    {
        return new static(...$args);
    }

    public function __construct(
        string $label = "",
        string $name = "",
        string $value = ""
    )
    {
        $this->type = "text";
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
    }

    public function email()
    {
        $this->type = "email";
        return $this;
    }

    public function long()
    {
        $this->type = "textarea";
        return $this;
    }

    public function hasCounter()
    {
        $this->hasCounter = true;
        return $this;
    }

    public function placeholder(string $placeholder = "")
    {
        if (Shoop::this($placeholder)->isEmpty()->reversed()->unfold()) {
            $this->placeholder = $placeholder;
        }
        return $this;
    }

    public function maxlength(int $maxlength = 0)
    {
        if ($maxlength > 0) {
            $this->maxlength = $maxlength;
        }
        return $this;
    }

    public function input()
    {
        $attr = Shoop::this($this->attrList())->append([
            "id {$this->name}",
            "name {$this->name}",
            "aria-describedby {$this->name}-label"
        ]);

        if ($this->type === "textarea") {
            $input = PHPUIKit::textarea($this->value)->attr(...$attr);

        } else {
            $input = PHPUIKit::input()->attr(
                ...$attr->append(["type {$this->type}"])
            );

        }

        if (Shoop::this($this->placeholder)->isEmpty()->reversed()->unfold()) {
            $input = $input->attr(
                ...Shoop::this($input->attrList())
                    ->append(["placeholder {$this->placeholder}"])
                );
        }

        if (Shoop::this($this->maxlength)->isEmpty()->reversed()->unfold()) {
            $input = $input->attr(
                ...Shoop::this($input->attrList())
                    ->append(["maxlength {$this->maxlength}"])
                );
        }

        if ($this->type !== "textarea" and
            Shoop::this($this->value)->isEmpty()->reversed()->unfold()
        ) {
            $input = $input->attr(
                ...Shoop::this($input->attrList())
                    ->append(["value {$this->value}"])
                );
        }

        if ($this->required) {
            $input = $input->attr(
                ...Shoop::this($this->attrList())
                    ->append(["required required"])
                );
        }

        $counter = (! $this->hasCounter)
            ? ""
            : PHPUIKit::span(
                PHPUIKit::i("{$this->maxlength}"),
                " characters remaining"
            )->attr("id {$this->name}-counter", "aria-live polite");

        return Shoop::this([$this->error(), $input, $counter]);
    }

    public function unfold(): string
    {
        $base = PHPUIKit::div($this->label(), ...$this->input());
        if (Shoop::this($this->errorMessage)->efIsEmpty()) {
            return $base->attr("is form-control");
        }
        return $base->attr("is form-control-with-errors");
    }
}
