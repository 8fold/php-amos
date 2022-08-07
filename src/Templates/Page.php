<?php
declare(strict_types=1);

namespace Eightfold\Amos\Templates;

use Eightfold\Amos\Contracts\Buildable;

use Eightfold\HTMLBuilder\Document;
use Eightfold\HTMLBuilder\Element;

use Eightfold\Amos\Implementations\Buildable as BuildableTrait;

use Eightfold\Amos\PageComponents\PageTitle;
use Eightfold\Amos\PageComponents\Favicons;

use Eightfold\Amos\Markdown;

class Page implements Buildable
{
    use BuildableTrait;

    public function build(): string
    {
        // $site = $this->site();
        return Document::create(
            PageTitle::create($this->site())->build()
        )->head(
            Element::meta()->omitEndTag()->props(
                'name viewport',
                'content width=device-width,initial-scale=1'
            ),
            Favicons::create(
                themeColor: '#0780A7',
                path: '/favicons',
                msAppTileColor: '#008181',
                safariTabColor: '#008181'
            ),
            Element::link()->omitEndTag()
                ->props(
                    'rel stylesheet',
                    'href /css/styles.min.css',
                    'type text/css'
                ),
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
