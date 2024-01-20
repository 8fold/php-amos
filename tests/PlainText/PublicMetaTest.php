<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Path;

use Eightfold\Amos\PlainText\PublicMeta;

class PublicMetaTest extends BaseTestCase
{
    /**
     * @test
     * @group current
     */
    public function can_get_content_using_path(): void
    {
        $sut = PublicMeta::inRoot(parent::root());

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

        $expected = <<<json
        {
          "title": "L1 page",
          "created": "20230101",
          "priority": 1.0
        }

        json;

        $sut = PublicMeta::inRoot(
            parent::root(),
            Path::fromString('l1-page')
        );

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_content(): void
    {
        $sut = PublicMeta::inRoot(parent::root());

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

        $expected = <<<json
        {
          "title": "L1 page",
          "created": "20230101",
          "priority": 1.0
        }

        json;

        $sut = PublicMeta::inRoot(
            parent::root(),
            DIRECTORY_SEPARATOR . 'l1-page'
        );

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
