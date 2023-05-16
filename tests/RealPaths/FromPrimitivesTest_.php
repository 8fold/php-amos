<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\RealPaths;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\RealPaths as AmosRealPath;

class FromPrimitivesTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_root_path(): void
    {
        $content_root = $this->contentRoot();

        $expected = $content_root;

        $result = AmosRealPath\FromPrimitives::forDir($content_root);

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromPrimitives::forDir(
            $content_root,
            '/does-not-exist'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_path(): void
    {
        $expected = $this->contentRoot() . '/public';

        $result = AmosRealPath\FromPrimitives::forPublicDir(
            $this->contentRoot()
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromPrimitives::forPublicDir(
            $this->contentRoot(),
            '/non-existent'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_filename_at_path(): void
    {
        $expected = $this->contentRoot() . '/meta.json';

        $result = AmosRealPath\FromPrimitives::forFile(
            $this->contentRoot(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromPrimitives::forFile(
            $this->contentRoot(),
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
     */
    public function can_get_public_filename_at_path(): void
    {
        $expected = $this->contentRoot() . '/public/deeper-page/meta.json';

        $result = AmosRealPath\FromPrimitives::forPublicFile(
            $this->contentRoot(),
            'meta.json',
            '/deeper-page'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromPrimitives::forPublicFile(
            $this->contentRoot(),
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
     */
    public function can_get_paths_for_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json',
            $this->contentRoot() . '/meta.json'
        ];

        $result = AmosRealPath\FromPrimitives::forFilesNamed(
            $this->contentRoot(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_paths_for_public_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json'
        ];

        $result = AmosRealPath\FromPrimitives::forPublicFilesNamed(
            $this->contentRoot(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
