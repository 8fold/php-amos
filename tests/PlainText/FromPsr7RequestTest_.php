<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\PlainText\FromPsr7Request;

class FromPsr7RequestTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_public_file_contents(): void
    {
        $expected = <<<json
        {
          "title": "Root test content"
        }
        json;

        $result = FromPsr7Request::inPublicFile(
            $this->contentRoot(),
            '/meta.json',
            $this->request()
        );

        $this->assertSame(
            $expected . "\n",
            $result
        );
    }
}
