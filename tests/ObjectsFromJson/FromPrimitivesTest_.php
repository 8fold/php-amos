<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\ObjectsFromJson;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\ObjectsFromJson\FromPrimitives;

use StdClass;

class FromPrimitivesTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_public_json_object(): void
    {
        $expected = new StdClass();
        $expected-> title = 'Root test content';

        $result = FromPrimitives::inPublicFile(
            $this->contentRoot(),
            '/meta.json'
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_json_object(): void
    {
        $expected = new StdClass();

        $result = FromPrimitives::inFile(
            $this->contentRoot(),
            '/meta.json'
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
