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

    private $titles = [];

    private bool $isReversed = false;

    public static function create(Store $store): PageTitle
    {
        return new PageTitle($store);
    }

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function reversed(): PageTitle
    {
        $this->isReversed = true;
        return $this;
    }

    public function build(): string
    {
        $titles = $this->titles();
        return $this->combineTitles($titles);
    }

    public function buildBookEnd(): string
    {
        $titles = $this->titles();

        $t = [];
        $t[] = array_shift($titles);
        $t[] = array_pop($titles);

        return $this->combineTitles($t);
    }

    public function __toString(): string
    {
        return $this->build();
    }

    private function combineTitles(array $titles): string
    {
        if ($this->isReversed()) {
            $titles = array_reverse($titles);
        }
        return implode(' | ', $titles);
    }

    /**
     * @return array<string> [description]
     */
    private function titles(): array
    {
        if (count($this->titles) === 0) {
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

            $this->titles = $titles;
        }
        return $this->titles;
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
