<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\PlainText\PrivateJson;

use Eightfold\Amos\FileSystem\Path;

class PrivateJsonTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_get_content(): void
    {
        $sut = PrivateJson::inRoot(
            parent::root(),
            'meta.json',
            Path::fromString()
        );

        $expected = <<<json
        {}

        json;

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );

        $expected = <<<json
        {
          "links": [
            "/ Home"
          ]
        }

        json;

        $sut = PrivateJson::inRoot(
            parent::root(),
            'meta.json',
            Path::fromString('navigation')
        );

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $sut = PrivateJson::inRoot(
            parent::root(),
            '',
            Path::fromString('l1-page')
        );

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
