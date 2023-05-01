<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Amos\Site;

class SiteTest extends TestCase
{
    /**
     * @test
     */
    public function page_title_does_not_duplicate_root(): void
    {
        $expected = ['Root test content'];

        $result = Site::init(
            'https://ex.ample',
            __DIR__ . '/test-content'
        )->titles('/');

        $this->assertSame(
            $expected,
            $result
        );

        $expected = [
            'Deeper page',
            'Root test content'
        ];

        $result = Site::init(
            'https://ex.ample',
            __DIR__ . '/test-content'
        )->titles('/deeper-page');

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_initialize_site(): void
    {
        $this->assertNotNull(
            Site::init(
                'https://ex.ample',
                __DIR__ . '/test-content'
            )
        );
    }
}
