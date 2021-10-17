<?php

namespace Eightfold\LaravelMarkup\Elements\FormControls;

use Eightfold\Markup\UIKit as PHPUIKit;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Foldable\Foldable;

class Select extends FormControl
{
    static public function fold(...$args): Foldable
    {
        return new static(...$args);
    }

    public function __construct(string $label, string $name, $value = "")
    {
        $this->type = "dropdown";
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
    }

    public function options(...$options)
    {
        $this->content = $options;
        return $this;
    }

    public function radio()
    {
        $this->type = "radio";
        return $this;
    }

    public function checkbox()
    {
        $this->type = "checkbox";
        return $this;
    }

    public function unfold(): string
    {
        $base = "";
        switch ($this->type) {
            case "checkbox":
                $base = $this->checkboxControl();
                break;

            case "radio":
                $base = $this->radioControl();
                break;

            default:
                $base = $this->selectControl();
                break;
        }

        if (Shoop::this($this->errorMessage)->efIsEmpty()) {
            return $base->attr("is form-control")->unfold();
        }
        return $base->attr("is form-control-with-errors")->unfold();
    }

    private function radioControl()
    {
        $options = Shoop::this($this->content)->each(function($v, $m, &$build) {
            if (Shoop::this($v)->efIsArray()) {
                $build[] = Shoop::this($v)->each(fn($v) => $this->option($v))->unfold();

            } else {
                $build[] = $this->option($v);

            }
        })->unfold();

        return PHPUIKit::fieldset(
            PHPUIKit::legend($this->label)->attr("id {$this->name}-legend"),
            $this->error(),
            PHPUIKit::listWith(...$options)
        );
    }

    private function checkboxControl()
    {
        $options = Shoop::this($this->content)->each(function($v, $m, &$build) {
            if (Shoop::this($v)->efIsArray()) {
                $build[] = Shoop::this($v)->each(fn($v) => $this->option($v))->unfold();

            } else {
                $build[] = $this->option($v);

            }
        })->unfold();

        return PHPUIKit::fieldset(
            PHPUIKit::legend($this->label)->attr("id {$this->name}-legend"),
            $this->error(),
            PHPUIKit::listWith(...$options)
        );
    }

    private function selectControl()
    {
        $select = PHPUIKit::select(...Shoop::this($this->content)->each(function($option) {
                if (Shoop::this($option)->efIsArray()) {
                    // Format: [
                    //      "Group title",
                    //      "/path Title",
                    //      "/path/two Title Two",
                    //      "..."
                    // ]
                    $group = Shoop::this($option);
                    $label = $group->first()->unfold();
                    $options = $group->dropFirst();
                    return PHPUIKit::optgroup(
                        ...$options->each(fn($option) => $this->option($option))
                    )->attr("label {$group->first()->unfold()}");
                }
                return $this->option($option);
            })
        )->attr("id {$this->name}", "name {$this->name}");

        if ($this->required) {
            $select = $select->attr(
                ...Shoop::this($this->attrList())->append(["required required"])
            );
        }

        return PHPUIKit::div($this->label(), $this->error(), $select);
    }

    private function option($option)
    {
        list($value, $title) = Shoop::this($option)->divide(" ", false, 2);
        if ($this->type === "checkbox" or $this->type === "radio") {
            $label = PHPUIKit::label($title)->attr("for {$value}");

            if ($this->type === "checkbox") {
                $radio = PHPUIKit::input()->attr(
                    "type checkbox",
                    "name {$this->name}[]",
                    "value {$value}",
                    "id {$value}"
                );

            } elseif ($this->type === "radio") {
                $radio = PHPUIKit::input()->attr(
                    "type radio",
                    "name {$this->name}",
                    "value {$value}",
                    "id {$value}"
                );

            }

            if ($this->required) {
                $radio = $radio->attr(
                    ...Shoop::this($this->attrList())
                        ->append(["required required"])->unfold()
                );
            }

            if ($this->type === "radio" and $this->value === $value) {
                $radio = $radio->attr(
                    ...Shoop::this($this->attrList())->append(["checked checked"])
                );

            } elseif ($this->type === "checkbox"
                and Shoop::this($this->value)->has($value)->efToBoolean()
            ) {
                $radio = $radio->attr(
                    ...Shoop::this($this->attrList())->append(["checked checked"])
                );
            }

            return $label . $radio;
        }

        $option = PHPUIKit::option($title)->attr("value {$value}");
        if ($this->value === $value) {
            $option = $option->attr(
                ...Shoop::this($this->attrList())->append(["selected selected"])
            );
        }
        return $option;
    }
}
