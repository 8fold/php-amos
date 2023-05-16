<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\RealPaths;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\RealPaths as AmosRealPath;

use Eightfold\Amos\Site;

class FromSiteTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_root_path(): void
    {
        $content_root = $this->contentRoot();

        $expected = $content_root;

        $result = AmosRealPath\FromSite::forDir($this->site());

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromSite::forDir(
            $this->site(),
            '/does-not-exist'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_public_path(): void
    {
        $expected = $this->contentRoot() . '/public';

        $result = AmosRealPath\FromSite::forPublicDir(
            $this->site()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_filename_at_path(): void
    {
        $expected = $this->contentRoot() . '/meta.json';

        $result = AmosRealPath\FromSite::forFile(
            $this->site(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromSite::forFile(
            $this->site(),
            'meta.json',
            '/non-existent'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_public_filename_at_path(): void
    {
        $expected = $this->contentRoot() . '/public/deeper-page/meta.json';

        $result = AmosRealPath\FromSite::forPublicFile(
            $this->site('/deeper-page'),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromSite::forPublicFile(
            $this->site('/non-existent'),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_paths_for_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/navigation/meta.json',
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json',
            $this->contentRoot() . '/meta.json'
        ];

        $result = AmosRealPath\FromSite::forFilesNamed(
            $this->site(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_paths_for_public_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json'
        ];

        $result = AmosRealPath\FromSite::forPublicFilesNamed(
            $this->site(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
