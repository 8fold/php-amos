<?php

declare(strict_types=1);

namespace Eightfold\Amos\Forms\FormControls;

use Eightfold\HTMLBuilder\Element as HtmlElement;

class Select extends FormControl
{
    /**
     * @var array<string>
     */
    protected $value = [];

    /**
     * @var array<string|array<string>>
     */
    private array $content = [];

    /**
     * @param  array<string>  $value [description]
     */
    public static function create(
        string $label,
        string $name,
        array $value = []
    ): Select {
        return new Select($label, $name, $value);
    }

    /**
     * @param array<string>  $value [description]
     */
    public function __construct(string $label, string $name, array $value = [])
    {
        $this->type = 'dropdown';
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param  string|array<string> $options [description]
     */
    public function options(...$options): Select
    {
        $this->content = $options;
        return $this;
    }

    public function radio(): Select
    {
        $this->type = 'radio';
        return $this;
    }

    public function checkbox(): Select
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

    private function checkboxControl(): HtmlElement
    {
        die('Checkbox not reset yet');
        // $options = Shoop::this($this->content)->each(
        // function ($v, $m, &$build) {
        //     if (Shoop::this($v)->efIsArray()) {
        //         $build[] = Shoop::this($v)->each(fn($v) => $this
        //         ->option($v))->unfold();

        //     } else {
        //         $build[] = $this->option($v);

        //     }
        // })->unfold();

        // return PHPUIKit::fieldset(
        //     PHPUIKit::legend($this->label)->attr("id {$this->name}-legend"),
        //     $this->error(),
        //     PHPUIKit::listWith(...$options)
        // );
    }

    private function radioControl(): HtmlElement
    {
        $options = [];
        foreach ($this->content as $option) {
            if (is_array($option)) {
                foreach ($option as $o) {
                    $options[] = $this->option($o);
                }

            } else {
                $options[] = $this->option($option);

            }
        }

        return HtmlElement::fieldset(
            HtmlElement::legend($this->label)->props("id {$this->name}-legend"),
            $this->error(),
            HtmlElement::ul(...$options)
        )->props('is form-control');
    }

    private function selectControl(): HtmlElement
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
            if (is_array($option)) {
                $group = $option;
                $label = array_shift($group);
                $opts = $group;

                $o = [];
                foreach ($opts as $opt) {
                    $o[] = $this->option($opt);
                }

                $options[] = HtmlElement::optgroup(...$o)
                    ->props("label {$label}");

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
    }

    private function option(string $option): HtmlElement
    {
        list($value, $title) = explode(' ', $option, 2);
        if ($this->type === 'dropdown') {
            $option = HtmlElement::option($title)->props("value {$value}");
            if (in_array($value, $this->value)) {
                $option = $option->props('selected selected');
            }
            return $option;
        }

        $label = HtmlElement::label($title)->props("for {$value}");

        $props = [
            "value {$value}",
            "id {$value}"
        ];

        if ($this->required) {
            $props[] = 'required required';
        }

        if (in_array($value, $this->value)) {
            $props[] = 'checked checked';
        }

        if ($this->type === 'checkbox') {
            $props[] = 'type checkbox';
            $props[] = "name {$this->name}[]";

        } elseif ($this->type === 'radio') {
            $props[] = 'type radio';
            $props[] = "name {$this->name}";

        }

        return HtmlElement::li(
            $label,
            HtmlElement::input()->omitEndTag()->props(...$props)
        );
        // if ($this->type === 'checkbox' or $this->type === 'radio') {
        //

        //     if ($this->type === 'checkbox') {
        //         $radio = PHPUIKit::input()->attr(
        //         );

        //     } elseif ($this->type === 'radio') {
        //         $radio = PHPUIKit::input()->attr(
        //             'type radio',
        //             "name {$this->name}",
        //         );

        //     }

        //     if ($this->type === 'radio' and $this->value === $value) {
        //         $radio = $radio->attr(
        //             ...Shoop::this($this->attrList())->append(['checked checked'])
        //         );

        //     } elseif (
        //         $this->type === 'checkbox' and
        //         Shoop::this($this->value)->has($value)->efToBoolean()
        //     ) {
        //         $radio = $radio->attr(
        //             ...Shoop::this($this->attrList())->append(['checked checked'])
        //         );
        //     }

        //     return $label . $radio;
        // }
    }
}
