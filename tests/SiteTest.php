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
