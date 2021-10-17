<?php

namespace Eightfold\Amos\Forms\FormControls;

use Eightfold\HTMLBuilder\Element as HtmlElement;

// use Eightfold\Markup\UIKit as PHPUIKit;

// use Eightfold\ShoopShelf\Shoop;

// use Eightfold\Foldable\Foldable;

class Select extends FormControl
{
    protected $value = [];

    public static function create(
        string $label,
        string $name,
        $value = []
    ): Select {
        return new Select($label, $name, $value);
    }

    public function __construct(string $label, string $name, $value = [])
    {
        $this->type = 'dropdown';
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
        $this->type = 'radio';
        return $this;
    }

    public function checkbox()
    {
        $this->type = 'checkbox';
        return $this;
    }

    public function build(): string
    {
        $control = '';
        switch ($this->type) {
            case 'checkbox':
                $control = $this->checkboxControl();
                break;

            case 'radio':
                $control = $this->radioControl();
                break;

            default:
                $control = $this->selectControl();
                break;
        }

        if (strlen($this->errorMessage) > 0) {
            $control = $control->props('is form-control-with-errors');
        }

        return $control->build();
    }

    private function checkboxControl()
    {
        die('Not reset yet');
        $options = Shoop::this($this->content)->each(function ($v, $m, &$build) {
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

    private function radioControl()
    {
        die('Not reset yet');
        $options = Shoop::this($this->content)->each(function ($v, $m, &$build) {
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
        $props = [
            "id {$this->name}",
            "name {$this->name}"
        ];

        if ($this->required) {
            $props[] = 'required required';
        }

        $options = [];
        foreach ($this->content as $option) {
            // TODO: Option group
            if (is_array($option)) {

            } else {
                $options[] = $this->option($option);

            }
        }

        $select = HtmlElement::select(...$options)->props(...$props);

        $control = HtmlElement::div(
            $this->label(),
            $this->error(),
            $select
        )->props('is form-control');

        if (strlen($this->errorMessage) > 0) {
            $control = $control->props('is form-control-with-errors');
        }

        return $control;

        // $select = HtmlElement::select(
        //     ...Shoop::this($this->content)->each(function ($option) {
        //         if (Shoop::this($option)->efIsArray()) {
        //             // Format: [
        //             //      "Group title",
        //             //      "/path Title",
        //             //      "/path/two Title Two",
        //             //      "..."
        //             // ]
        //             $group = Shoop::this($option);
        //             $label = $group->first()->unfold();
        //             $options = $group->dropFirst();
        //             return PHPUIKit::optgroup(
        //                 ...$options->each(fn($option) => $this->option($option))
        //             )->attr("label {$group->first()->unfold()}");
        //         }
        //         return $this->option($option);
        //     })
        // )->attr("id {$this->name}", "name {$this->name}");

        // if ($this->required) {
        //     $select = $select->attr(
        //         ...Shoop::this($this->attrList())->append(["required required"])
        //     );
        // }

        // return PHPUIKit::div($this->label(), $this->error(), $select);
    }

    private function option($option): HtmlElement
    {
        list($value, $title) = explode(' ', $option, 2);
        if ($this->type === 'checkbox' or $this->type === 'radio') {
            $label = PHPUIKit::label($title)->attr("for {$value}");

            if ($this->type === 'checkbox') {
                $radio = PHPUIKit::input()->attr(
                    'type checkbox',
                    "name {$this->name}[]",
                    "value {$value}",
                    "id {$value}"
                );

            } elseif ($this->type === 'radio') {
                $radio = PHPUIKit::input()->attr(
                    'type radio',
                    "name {$this->name}",
                    "value {$value}",
                    "id {$value}"
                );

            }

            if ($this->required) {
                $radio = $radio->attr(
                    ...Shoop::this($this->attrList())
                        ->append(['required required'])->unfold()
                );
            }

            if ($this->type === 'radio' and $this->value === $value) {
                $radio = $radio->attr(
                    ...Shoop::this($this->attrList())->append(['checked checked'])
                );

            } elseif (
                $this->type === 'checkbox' and
                Shoop::this($this->value)->has($value)->efToBoolean()
            ) {
                $radio = $radio->attr(
                    ...Shoop::this($this->attrList())->append(['checked checked'])
                );
            }

            return $label . $radio;
        }

        $option = HtmlElement::option($title)->props("value {$value}");
        if (in_array($value, $this->value)) {
            $option = $option->props('selected selected');
        }
        return $option;
    }
}
