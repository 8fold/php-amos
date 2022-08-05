<?php
declare(strict_types=1);

namespace Eightfold\Amos\Templates;

use Eightfold\Amos\Contracts\Buildable;

use Eightfold\HTMLBuilder\Document;
use Eightfold\HTMLBuilder\Element;

use Eightfold\Amos\Implementations\Buildable as BuildableTrait;

use Eightfold\Amos\PageComponents\PageTitle;

use Eightfold\Amos\Markdown;

class Page implements Buildable
{
    use BuildableTrait;

    public function build(): string
    {
        // $site = $this->site();
        return Document::create(
            PageTitle::create($this->site())->build()
        )->body(
            Markdown::convert(
                $this->site(),
                $this->site()->content(
                    $this->site()->requestPath()
                )
            )
        )->build();
    }
}
