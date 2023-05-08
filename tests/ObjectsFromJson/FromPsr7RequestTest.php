<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\ObjectsFromJson;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\ObjectsFromJson\FromPsr7Request;

use StdClass;

class FromPsr7RequestTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_public_json_object(): void
    {
        $expected = new StdClass();
        $expected->title = 'Root test content';

        $result = FromPsr7Request::inPublicFile(
            $this->contentRoot(),
            '/meta.json',
            $this->request()
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
