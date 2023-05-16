<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Traversable;
use DateTime;
use SplFileInfo;

use Eightfold\XMLBuilder\Document;
use Eightfold\XMLBuilder\Element;

use Eightfold\Amos\Php\Interfaces\Stringable;

use Eightfold\Amos\Site;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;

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

    /**
     * @param Traversable<string, SplFileInfo> $traversable
     */
    public static function create(
        Traversable $traversable,
        Site $site
    ): self {
        return new self($traversable, $site);
    }

    /**
     * @param Traversable<string, SplFileInfo> $traversable
     */
    final private function __construct(
        private readonly Traversable $traversable,
        private readonly Site $site
    ) {
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

    public function toString(): string
    {
        $content_root = $this->site()->contentRoot();
        $public_root  = $this->site()->publicRoot();
        $domain = $this->site()->domain();

        $urls = [];
        $iterator = $this->traversable;
        foreach ($iterator as $meta_file_path) {
            $path = str_replace(
                [$public_root, '/meta.json'],
                ['', ''],
                $meta_file_path->getRealPath()
            );

            $elements = [];

            $elements[] = Element::loc($domain . $path . '/');

            $meta = PublicMeta::inRoot($content_root, $path);

            if ($meta->hasProperty('sitemap') and $meta->sitemap() === false) {
                $urls[] = '';
                continue;
            }

            $lastmod = false;
            if ($meta->hasProperty('updated')) {
                $lastmod = $meta->updated();

            } elseif ($meta->hasProperty('created')) {
                $lastmod = $meta->created();

            }

            if (is_string($lastmod)) {
                $date = date_create($lastmod);
                if (
                    $date !== false and
                    is_a($date, DateTime::class)
                ) {
                    $elements[] = Element::lastmod(
                        $date->format('Y-m-d')
                    );
                }
            }

            $priority = $this->defaultPriority();
            if ($meta->hasProperty('priority')) {
                $priority = $meta->priority();
            }
            $elements[] = Element::priority(strval($priority));

            $urls[] = Element::url(...$elements);
        }

        return (string) Document::urlset(
            ...$urls
        )->props('xmlns ' . self::SCHEMA);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
