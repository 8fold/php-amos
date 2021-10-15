<?php

declare(strict_types=1);

namespace Eightfold\Amos\PageComponents;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\Amos\Store;

class PageTitle implements Buildable
{
    /**
     * @var Store
     */
    private $store;

    private string $titleStyle = 'page';

    private bool $isReversed = false;

    public static function create(Store $store): PageTitle
    {
        return new PageTitle($store);
    }

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function bookEnd(): PageTitle
    {
        $this->titleStyle = 'book-end';
        return $this;
    }

    public function reversed(): PageTitle
    {
        $this->isReversed = true;
        return $this;
    }

    public function build(): string
    {
        $titles = $this->titles();
        if ($this->titleStyle === 'book-end' and count($titles) > 2) {
            $t = [];
            $t[] = array_shift($titles);
            $t[] = array_pop($titles);

            $titles = $t;
        }
        return implode(' | ', $titles);
    }

    public function __toString(): string
    {
        return $this->build();
    }

    /**
     * @return array<string> [description]
     */
    private function titles(): array
    {
        $titles = [];
        $s = $this->store();
        while (! $s->isRoot()) {
            $m = $s->markdown();
            if (is_object($m)) {
                $titles[] = $m->title();
            }

            $s = $s->up();
        }

        if ($s->isRoot()) {
            $m = $s->markdown();
            if (is_object($m)) {
                $titles[] = $m->title();
            }
        }

        if ($this->isReversed()) {
            return array_reverse($titles);
        }
        return $titles;
    }

    private function isReversed(): bool
    {
        return $this->isReversed;
    }

    private function store(): Store
    {
        return $this->store;
    }
}
