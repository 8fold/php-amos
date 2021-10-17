<?php

namespace Eightfold\Amos\Forms;

use Eightfold\XMLBuilder\Contracts\Buildable;
use Eightfold\XMLBuilder\Implementations\Properties as PropertiesImp;

use Eightfold\HTMLBuilder\Element as HtmlElement;

class Form implements Buildable
{
    use PropertiesImp;

    private string $method = 'post';

    private string $action = '/';

    private $submitButton;

    private $csrfToken;

    private array $content = [];

    public static function create(string $methodAction = 'post /'): Form
    {
        return new Form($methodAction);
    }

    public function __construct($methodAction = 'post /')
    {
        list($method, $action) = explode(' ', $methodAction, 2);
        $this->method = $method;
        $this->action = $action;
    }

    public function content(Buildable ...$content)
    {
        $this->content = $content;
        return $this;
    }

    public function submitButtonDetails(
        string $label = "Submit",
        array $props = []
    ): Form {
        $this->submitButton = HtmlElement::button($label)->props(...$props);
        return $this;
    }

    public function csrfTokenDetails(
        string $value,
        string $name = '_token'
    ): Form {
        $this->csrfToken = HtmlElement::input()
            ->omitEndTag()
            ->props('type hidden', 'name ' . $name, 'value ' . $value);
        return $this;
    }

    public function build(): string
    {
        $content = [
            $this->csrfToken(),
            $this->submitButton()
        ];

        $content = array_merge($this->content, $content);

        return HtmlElement::form(
            ...$content
        )->props(
            'method ' . $this->method,
            'action ' . $this->action,
            ...$this->properties()
        )->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }

    private function submitButton(): HtmlElement
    {
        if ($this->submitButton === null) {
            $this->submitButtonDetails();
        }
        return $this->submitButton;
    }

    private function csrfToken()
    {
        if ($this->csrfToken === null) {
            $this->csrfToken = '';
        }
        return $this->csrfToken;
    }
}
