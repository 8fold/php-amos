<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Directories;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use SplFileInfo;

class PublicTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function is_expected_qualified_path(): void
    {
        $expected = (new SplFileInfo(parent::BASE . '/public'))->getRealPath();

        $result = parent::publicRoot()->toString();

        $this->assertSame(
            $expected,
            $result,
            $expected . ' is not the same as ' . $result
        );
    }

    /**
     * @test
     * @group oop
     */
    public function can_check_existence(): void
    {
        $sut = PublicRoot::inRoot(
            parent::root()
        );

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertTrue(
            $result
        );

        $result = $sut->isDir();

        $this->assertTrue(
            $result
        );

        $result = $sut->notFound();

        $this->assertFalse(
            $result
        );
    }
}
