<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\RealPaths;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\RealPaths as AmosRealPath;

class ForMetadataFilesTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_meta_file_at_root(): void
    {
        $expected = $this->contentRoot() . '/meta.json';

        $result = AmosRealPath\ForMetadataFiles::forFile(
            $this->contentRoot()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_meta_file(): void
    {
        $expected = $this->contentRoot() . '/public/deeper-page/meta.json';

        $result = AmosRealPath\ForMetadataFiles::forPublicFile(
            $this->contentRoot(),
            '/deeper-page'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_paths_for_public_meta_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json'
        ];

        $result = AmosRealPath\ForMetadataFiles::inPublic(
            $this->contentRoot()
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = [
            $this->contentRoot() . '/public/skipping-sitemap/content.md'
        ];

        $result = AmosRealPath\ForContentFiles::inPublic(
            $this->contentRoot()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
