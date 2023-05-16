<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\ObjectsFromJson;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\ObjectsFromJson\FromPsr7Uri;

use StdClass;

class FromPsr7UriTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_public_json_object(): void
    {
        $expected = new StdClass();
        $expected-> title = 'Root test content';

        $result = FromPsr7Uri::inPublicFile(
            $this->contentRoot(),
            '/meta.json',
            $this->request()->getUri()
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
