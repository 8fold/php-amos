<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Externals;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\Externals\Finder;

use function Eightfold\Amos\real_path_for_public_file;
use function Eightfold\Amos\real_paths_for_public_meta_files;

class FinderTest extends TestCase
{
    /**
     * @test
     */
    public function is_expected_array(): void
    {
        $expected = [
            real_path_for_public_file(
                $this->site()->contentRoot(),
                'meta.json',
                '/deeper-page'
            ),
            real_path_for_public_file(
                $this->site()->contentRoot(),
                'meta.json',
                '/skipping-sitemap'
            ),
            real_path_for_public_file(
                $this->site()->contentRoot(),
                'meta.json'
            )
        ];

        $result = real_paths_for_public_meta_files(
            $this->site()->contentRoot()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @deprecated
     */
    public function deprecated_is_expected_array(): void
    {
        $expected = [
            $this->site()->publicRoot() . '/deeper-page/meta.json',
            $this->site()->publicRoot() . '/skipping-sitemap/meta.json',
            $this->site()->publicRoot() . '/meta.json',
        ];

        $result = Finder::allMetaPaths($this->site());

        $this->assertSame(
            $expected,
            $result
        );
    }
}
