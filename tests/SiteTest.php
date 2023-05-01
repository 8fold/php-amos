<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

class SiteTest extends TestCase
{
    /**
     * @test
     */
    public function page_title_does_not_duplicate_root(): void
    {
        $expected = ['Root test content'];

        $result = $this->site()->titles('/');

        $this->assertSame(
            $expected,
            $result
        );

        $expected = [
            'Deeper page',
            'Root test content'
        ];

        $result = $this->site()->titles('/deeper-page');

        $this->assertSame(
            $expected,
            $result
        );
    }
}
