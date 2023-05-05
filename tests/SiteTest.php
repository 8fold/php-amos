<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use function Eightfold\Amos\titles_for_meta_objects_in_public_dir;

class SiteTest extends TestCase
{
    /**
     * @test
     */
    public function page_title_does_not_duplicate_root(): void
    {
        $expected = ['Root test content'];

        $result = titles_for_meta_objects_in_public_dir(
            $this->site()->contentRoot(),
            '/'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = [
            'Deeper page',
            'Root test content'
        ];

        $result = titles_for_meta_objects_in_public_dir(
            $this->site()->contentRoot(),
            '/deeper-page'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
