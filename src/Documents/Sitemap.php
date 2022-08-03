<?php
declare(strict_types=1);

namespace Eightfold\Amos\Documents;

use SplFileInfo;
use DateTime;

use Symfony\Component\Finder\Finder;

use Eightfold\XMLBuilder\Document;
use Eightfold\XMLBuilder\Element;

use Eightfold\Amos\Content;

use Eightfold\Amos\Site;

/**
 * https://www.sitemaps.org
 *
 * Regarding priority: Some search engines, specifically Google, do not pay
 * much attention to priority (or lastmod). By default, we set all pages to 0.5.
 *
 * In your meta.json files, you can use the priority member to overwrite the
 * default. We recommend the following:
 *
 * 1.0-0.8: Homepage, product information, landing pages.
 * 0.7-0.4: News articles, some weather services, blog posts, pages that no site
 *          would be complete without.
 * 0.3-0.0: FAQs, outdated info, old press releases, completely static pages that
 *          are still relevant enough to keep from deleting entirely.
 */
class Sitemap
{
    public static function create(Site $site): self
    {
        return new self($site);
    }

    final private function __construct(private Site $site)
    {
    }

    private function site(): Site
    {
        return $this->site;
    }

    public function build(): string
    {
        $metaFilePaths = (new Finder())->files()->name('meta.json')
            ->in($this->site()->publicRoot());

        $urls = [];
        foreach ($metaFilePaths as $metaFilePath) {
            $fullyQualifiedPath = $metaFilePath->getRealPath();
            $path = str_replace(
                [$this->site()->publicRoot(), '/meta.json'],
                ['', ''],
                $fullyQualifiedPath
            );

            $loc = $this->site()->domain() . $path . '/';

            $meta = $this->site()->meta(at: $path);
            if ($meta === false) {
                $urls[] = '';
                continue;
            }

            $lastmod = '';
            if (property_exists($meta, 'updated')) {
                $lastmod = $meta->updated;

            } elseif (property_exists($meta, 'created')) {
                $lastmod = $meta->created;

            }

            $date = date_create($lastmod);
            if (
                $date !== false and
                is_a($date, DateTime::class)
            ) {
                $lastmod = $date->format('Y-m-d');
            }

            $priority = 0.5;
            if (property_exists($meta, 'priority')) {
                $priority = $meta->priority;

            }

            $urls[] = Element::url(
                Element::loc($loc),
                Element::lastmod($lastmod),
                Element::priority(strval($priority))
            );
        }

        return Document::urlset(
            ...$urls
        )->props('xmlns http://www.sitemaps.org/schemas/sitemap/0.9')->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
