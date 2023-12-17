<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\ObjectsFromJson;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;

class PublicMetaTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_get_content(): void
    {
        $sut = PublicMeta::inRoot(parent::root());

        $expected = 'Root test content';

        $result = $sut->title();

        $this->assertSame(
            $expected,
            $result
        );

        $sut = PublicMeta::inRoot(parent::root(), '/l1-page');

        $expected = 1.0;

        $result = $sut->priority();

        $this->assertSame(
            $expected,
            $result
        );

        $sut = PublicMeta::inRoot(parent::root(), '/l1-page');

        $result = $sut->nonexistent();

        $this->assertFalse($result);
    }
}
