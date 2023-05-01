<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\Sitemap;

class SiteMapTest extends TestCase
{
    /**
     * @test
     */
    public function can_skip_sitemap(): void
    {
        $expected = <<<xml
        <?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://ex.ample/deeper-page/</loc><lastmod>2023-01-01</lastmod><priority>1</priority></url><url><loc>http://ex.ample/</loc><priority>0.5</priority></url></urlset>
        xml;

        $result = (string) Sitemap::create(
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
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://ex.ample/deeper-page/</loc><lastmod>2023-01-01</lastmod><priority>1</priority></url><url><loc>http://ex.ample/</loc><priority>0.75</priority></url></urlset>
        xml;

        $result = (string) Sitemap::create(
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
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://ex.ample/deeper-page/</loc><lastmod>2023-01-01</lastmod><priority>1</priority></url><url><loc>http://ex.ample/</loc><priority>0.5</priority></url></urlset>
        xml;

        $result = (string) Sitemap::create(
            $this->site()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
