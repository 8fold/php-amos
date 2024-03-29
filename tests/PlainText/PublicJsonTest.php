<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\PlainText\PublicJson;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;

class PublicJsonTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_get_content(): void
    {
        $sut = PublicJson::inRoot(
            parent::root(),
            Filename::fromString('meta.json'),
            Path::fromString()
        );

        $expected = <<<json
        {
          "title": "Root test content"
        }

        json;

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $sut = PublicJson::inRoot(
            parent::root(),
            Filename::fromString('meta.json'),
            Path::fromString('navigation')
        );

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $sut = PublicJson::inRoot(
            parent::root(),
            Filename::fromString(),
            Path::fromString('l1-page')
        );

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
