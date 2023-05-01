<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Externals;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\Externals\Finder;

use Eightfold\Amos\Site;

class FinderTest extends TestCase
{
    /**
     * @test
     */
    public function is_expected_array(): void
    {
        $expected = [
            $this->site()->publicRoot() . '/deeper-page/meta.json',
            $this->site()->publicRoot() . '/meta.json'
        ];

        $result = Finder::allMetaPaths(
            $this->site()
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
