<?php
declare(strict_types=1);

namespace Eightfold\Amos\PageComponents;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Psr\Http\Message\RequestInterface;

use Eightfold\Amos\Site;

use Eightfold\Amos\Content;
use Eightfold\Amos\Meta;

use Eightfold\Amos\Markdown;

class PageTitle implements Buildable
{
    public static function create(Site $site): PageTitle
    {
        return new self($site);
    }

    final private function __construct(private Site $site)
    {
    }

    public function site(): Site
    {
        return $this->site;
    }

    public function build(): string
    {
        $path = $this->site()->requestPath();

        $pathParts = explode('/', $path);
        $filtered  = array_filter($pathParts);

        $titles = [];
        while (count($filtered) > 0) {
            $path = '/' . implode('/', $filtered) . '/';
            $titles[] = $this->title(at: $path);

            array_pop($filtered);
        }

        $titles[] = $this->title(at: '/');

        $titles = array_filter($titles);

        return trim(implode(' | ', $titles));
    }

    public function __toString(): string
    {
        return $this->build();
    }

    private function title(string $at): string
    {
        $meta = $this->site()->meta(at: $at);
        if (is_object($meta) and property_exists($meta, 'title')) {
            return Markdown::convertTitle($meta->title);
        }
        return '';
    }
}
