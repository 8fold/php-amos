<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Stringable;

use Eightfold\XMLBuilder\Document;
use Eightfold\XMLBuilder\Element;

use Eightfold\Amos\Site;

use Eightfold\Amos\Externals\Finder;

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
class Sitemap implements Stringable
{
    private const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    private float $defaultPriority = 0.5;

    public static function create(Site $site): self
    {
        return new self($site);
    }

    final private function __construct(private readonly Site $site)
    {
    }

    public function withDefaultPriority(float $priority = 0.5): self
    {
        $this->defaultPriority = $priority;
        return $this;
    }

    private function site(): Site
    {
        return $this->site;
    }

    private function defaultPriority(): float
    {
        return $this->defaultPriority;
    }

    public function __toString(): string
    {
        $metaFilePaths = Finder::allMetaPaths($this->site());
        // $metaFilePaths = (new Finder())->files()->name('meta.json')
        //     ->in($this->site()->publicRoot());

        $urls = [];
        foreach ($metaFilePaths as $metaFilePath) {
            // $fullyQualifiedPath = $metaFilePath->getRealPath();
            $path = str_replace(
                [$this->site()->publicRoot(), '/meta.json'],
                ['', ''],
                $metaFilePath
            );

            $elements = [];

            $elements[] = Element::loc(
                $this->site()->domain() . $path . '/'
            );

            $meta = $this->site()->meta(at: $path);
            if ($meta === false) {
                $urls[] = '';
                continue;
            }

            $lastmod = false;
            if (property_exists($meta, 'updated')) {
                $lastmod = date_create($meta->updated);

            } elseif (property_exists($meta, 'created')) {
                $lastmod = date_create($meta->created);

            }

            if ($lastmod !== false) {
                $elements[] = Element::lastmod(
                    $lastmod->format('Y-m-d')
                );
            }

            if (property_exists($meta, 'priority')) {
                $priority = $meta->priority;

                $elements[] = Element::priority(strval($priority));

            } else {
                $priority = $this->defaultPriority();

                $elements[] = Element::priority(strval($priority));

            }

            $urls[] = Element::url(...$elements);
        }

        return (string) Document::urlset(
            ...$urls
        )->props('xmlns ' . self::SCHEMA);
    }
}
