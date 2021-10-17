<?php

namespace Eightfold\LaravelMarkup\Elements\FormControls;

use Eightfold\Markup\UIKit as PHPUIKit;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\Shoop;

class Password extends FormControl
{
    private $maxlength = 254;

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
        $this->type = "password";
        $this->label = $label;
        $this->name = $name;
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
        $input = PHPUIKit::input()->attr(...Shoop::this($this->attrList())->append([
                "id {$this->name}",
                "name {$this->name}",
                "type {$this->type}",
                "aria-describedby {$this->name}-label"
            ])->unfold()
        );

        if (Shoop::this($this->maxlength)->isEmpty()->reversed()->unfold()) {
            $input = $input->attr(...Shoop::this($input->attrList())->append([
                    "maxlength {$this->maxlength}"
                ])->unfold()
            );
        }

        if ($this->required) {
            $input = $input->attr(...Shoop::this($this->attrList())->append([
                    "required required"
                ])->unfold()
            );
        }

        return Shoop::this([$this->error(), $input]);
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
