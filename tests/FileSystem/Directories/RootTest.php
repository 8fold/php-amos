<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Directories;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Directories\Root;

use SplFileInfo;

use Eightfold\Amos\FileSystem\Path;

class RootTest extends BaseTestCase
{
    /**
     * @test
     */
    public function is_expected_qualified_path(): void
    {
        $expected = (new SplFileInfo(parent::BASE))->getRealPath();

        $result = parent::root()->toString();

        $this->assertSame(
            $expected,
            $result,
            $expected . ' is not the same as ' . $result
        );

        $realPath = (new SplFileInfo(parent::NONEXISTENT_BASE))->getRealPath();

        $expected = '';

        $result = parent::nonexistentRoot()->toString();

        $this->assertFalse($realPath);

        $this->assertSame(
            $expected,
            $result,
            $expected . ' is not the same as ' . $result
        );
    }

    /**
     * @test
     * @group current
     */
    public function can_check_existence_using_path(): void
    {
        $sut = Root::fromPath(
            Path::fromString(parent::BASE)
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

        $sut = parent::nonexistentRoot();

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertFalse(
            $result
        );

        $result = $sut->isDir();

        $this->assertFalse(
            $result
        );

        $result = $sut->notFound();

        $this->assertTrue(
            $result
        );
    }

    /**
     * @test
     */
    public function can_check_existence(): void
    {
        $sut = parent::root();

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

        $sut = parent::nonexistentRoot();

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertFalse(
            $result
        );

        $result = $sut->isDir();

        $this->assertFalse(
            $result
        );

        $result = $sut->notFound();

        $this->assertTrue(
            $result
        );
    }
}
