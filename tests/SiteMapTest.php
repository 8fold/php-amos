<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\Sitemap;

use Symfony\Component\Finder\Finder as SymfonyFinder;

use Traversable;

class SiteMapTest extends TestCase
{
    private function allPublicMetaFiles(): Traversable
    {
        return (new SymfonyFinder())->files()->name('meta.json')
            ->in(
                parent::publicRoot()->toString()
            );
    }

    /**
     * @test
     */
    public function can_skip_sitemap(): void
    {

        $expected = <<<xml
        <?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://ex.ample/l1-page/</loc><lastmod>2023-01-01</lastmod><priority>1</priority></url><url><loc>http://ex.ample/</loc><priority>0.5</priority></url></urlset>
        xml;

        $result = (string) Sitemap::create(
            $this->allPublicMetaFiles(),
            $this->site()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_change_default_priority(): void
    {
        $expected = <<<xml
        <?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://ex.ample/l1-page/</loc><lastmod>2023-01-01</lastmod><priority>1</priority></url><url><loc>http://ex.ample/</loc><priority>0.75</priority></url></urlset>
        xml;

        $result = (string) Sitemap::create(
            $this->allPublicMetaFiles(),
            $this->site()
        )->withDefaultPriority(0.75);

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function is_expected_xml(): void
    {
        $expected = <<<xml
        <?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://ex.ample/l1-page/</loc><lastmod>2023-01-01</lastmod><priority>1</priority></url><url><loc>http://ex.ample/</loc><priority>0.5</priority></url></urlset>
        xml;

        $result = (string) Sitemap::create(
            $this->allPublicMetaFiles(),
            $this->site()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
