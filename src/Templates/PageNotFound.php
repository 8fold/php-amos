<?php
declare(strict_types=1);

namespace Eightfold\Amos\Templates;

use Eightfold\Amos\Contracts\Buildable;

use Eightfold\HTMLBuilder\Document;
use Eightfold\HTMLBuilder\Element;

use Eightfold\Amos\Implementations\Buildable as BuildableTrait;

use Eightfold\Amos\PageComponents\PageTitle;

use Eightfold\Amos\Markdown;

class PageNotFound implements Buildable
{
    use BuildableTrait;

    public function build(): string
    {
        $markdown = $this->site()->textFile(
            named: 'content.md',
            at: '/errors/404'
        );
        if ($markdown === false) {
            $markdown = '';
        }
        return Document::create(
            'Page not found error (404)'
        )->body(
            Markdown::convert($this->site(), $markdown)
        )->build();
    }
}
