<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use function Eightfold\Amos\titles_for_meta_in_public_dir;

class SiteTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function has_expected_domain(): void
    {
        $expected = $this->domain();

        $result = $this->site()->domain();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
