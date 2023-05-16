<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\ObjectsFromJson;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\ObjectsFromJson\Meta;

use StdClass;

class MetaTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_meta_using_site(): void
    {
        $expected = new StdClass();
        $expected->links = ['/ Home'];

        $result = Meta::fromSite($this->site(), '/navigation');

        $this->assertEquals(
            $expected,
            $result
        );

        $expected = new StdClass();
        $expected-> title = 'Root test content';

        $result = Meta::inPublicFromSite($this->site());

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
