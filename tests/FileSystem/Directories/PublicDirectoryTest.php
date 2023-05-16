<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Directories\PublicDirectory;

use SplFileInfo;

class PublicDirectoryTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function is_expected_qualified_path(): void
    {
        $expected = (new SplFileInfo(parent::PUBLIC_BASE . '/deeper-page'))
            ->getRealPath();

        $sut = PublicDirectory::inRoot(
            parent::root(),
            '/deeper-page'
        );

        $result = $sut->toString();

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
        $sut = PublicDirectory::inRoot(
            parent::root(),
            '/deeper-page'
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
