<?php

declare(strict_types=1);

namespace Eightfold\Amos\PageComponents;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\Amos\Store;

class Copyright implements Buildable
{
    private string $name = '';

    /**
     * @var string|int
     */
    private $startYear = '';

    /**
     * @param  string|int $startYear [description]
     */
    public static function create(string $name, $startYear = ''): Copyright
    {
        return new Copyright($name, $startYear);
    }

    /**
     * @param string|int $startYear [description]
     */
    public function __construct(string $name, $startYear = '')
    {
        $this->name      = $name;
        $this->startYear = $startYear;
    }

    private function name(): string
    {
        return $this->name;
    }

    /**
     * @return string|int [description]
     */
    private function startYear()
    {
        return $this->startYear;
    }

    private function hasStartYear(): bool
    {
        return strlen(strval($this->startYear())) > 0;
    }

    public function build(): string
    {
        $yearString = [];
        if ($this->hasStartYear()) {
            $yearString[] = $this->startYear();
        }
        $yearString[] = date('Y');
        $yearString = implode('–', $yearString);

        $name = $this->name();

        return <<<html
            <p>Copyright © {$yearString} {$name}. All rights reserved.</p>
            html;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
