<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Directories;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Directories\PublicDirectory;

use SplFileInfo;

use Eightfold\Amos\FileSystem\Path;

class PublicDirectoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function is_expected_qualified_path(): void
    {
        $expected = (new SplFileInfo(
            parent::PUBLIC_BASE . DIRECTORY_SEPARATOR . 'l1-page')
        )->getRealPath();

        $sut = PublicDirectory::inRoot(
            parent::root(),
            Path::fromString('l1-page')
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
     */
    public function can_check_existence_using_path(): void
    {
        $sut = PublicDirectory::inRoot(
            parent::root(),
            Path::fromString('l1-page')
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
