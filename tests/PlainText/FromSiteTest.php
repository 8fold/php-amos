<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\PlainText\FromSite;

class FromSiteTest extends TestCase
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

        $result = FromSite::inPublicFile(
            $this->site(),
            '/meta.json'
        );

        $this->assertSame(
            $expected . "\n",
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_file_contents(): void
    {
        $expected = <<<json
        {}
        json;

        $result = FromSite::inFile($this->site(), '/meta.json');

        $this->assertSame(
            $expected . "\n",
            $result
        );

        $expected = <<<json
        {
          "title": "Root test content"
        }
        json;

        $result = FromSite::inFile(
            $this->site(),
            '/meta.json',
            '/public'
        );

        $this->assertSame(
            $expected . "\n",
            $result
        );
    }
}
