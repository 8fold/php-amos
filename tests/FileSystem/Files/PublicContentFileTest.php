<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Files;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Files\PublicContentFile;

use Eightfold\Amos\FileSystem\Path;

use Eightfold\Amos\FileSystem\Directories\Root;

class PublicContentFileTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_check_existence_using_path(): void
    {
        $root = Root::fromString(
            __DIR__ .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'test-content');

        $sut = PublicContentFile::inRoot(
            $root,
            Path::fromString()
        );

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertTrue(
            $result
        );

        $result = $sut->isFile();

        $this->assertTrue(
            $result
        );

        $result = $sut->notFound();

        $this->assertFalse(
            $result
        );

        $sut = PublicContentFile::inRoot(
            $root,
            Path::fromString('l1-page')
        );

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertFalse(
            $result
        );

        $result = $sut->isFile();

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
        $root = Root::fromString(
            __DIR__ .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'test-content');

        $sut = PublicContentFile::inRoot($root, Path::fromString());

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertTrue(
            $result
        );

        $result = $sut->isFile();

        $this->assertTrue(
            $result
        );

        $result = $sut->notFound();

        $this->assertFalse(
            $result
        );

        $sut = PublicContentFile::inRoot(
            $root,
            Path::fromString('l1-page')
        );

        $this->assertNotNull(
            $sut
        );

        $result = $sut->toBool();

        $this->assertFalse(
            $result
        );

        $result = $sut->isFile();

        $this->assertFalse(
            $result
        );

        $result = $sut->notFound();

        $this->assertTrue(
            $result
        );
    }
}
