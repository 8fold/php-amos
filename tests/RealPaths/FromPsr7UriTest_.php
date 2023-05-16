<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\RealPaths;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\RealPaths as AmosRealPath;

use Nyholm\Psr7\Uri;

class FromPsr7UriTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_public_path(): void
    {
        $content_root = $this->contentRoot();

        $expected = $content_root . '/public';

        $result = AmosRealPath\FromPsr7Uri::forPublicDir(
            $content_root,
            new Uri($this->domain())
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromPsr7Uri::forPublicDir(
            $content_root,
            new Uri($this->domain() . '/does-not-exist')
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
        $expected = $this->contentRoot() . '/public/meta.json';

        $result = AmosRealPath\FromPsr7Uri::forPublicFile(
            $this->contentRoot(),
            'meta.json',
            new Uri($this->domain())
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = AmosRealPath\FromPsr7Uri::forPublicFile(
            $this->contentRoot(),
            'meta.json',
            new Uri($this->domain() . '/does-not-exist')
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

        $result = AmosRealPath\FromPsr7Uri::forPublicFilesNamed(
            $this->contentRoot(),
            'meta.json',
            new Uri($this->domain())
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
