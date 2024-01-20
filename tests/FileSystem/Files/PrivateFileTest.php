<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Files;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Files\PrivateFile;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PrivateDirectory;

class PrivateFileTest extends BaseTestCase
{
    /**
     * @test
     * @group current
     */
    public function can_check_existence_using_path(): void
    {
        $root = Root::fromString(
            __DIR__ .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'test-content');

        $sut = PrivateFile::inRoot(
            $root,
            'meta.json',
            Path::fromString(
                DIRECTORY_SEPARATOR . 'navigation' . DIRECTORY_SEPARATOR
            )
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

        $sut = PrivateFile::inRoot(
            $root,
            'meta.json',
            DIRECTORY_SEPARATOR . 'navigation' . DIRECTORY_SEPARATOR
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
    }
}
