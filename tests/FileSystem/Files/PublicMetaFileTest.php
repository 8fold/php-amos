<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Files;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Files\PublicMetaFile;

use Eightfold\Amos\FileSystem\Directories\Root;

class PublicMetaFileTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_check_existence(): void
    {
        $root = Root::fromString(__DIR__ . '/../../test-content');

        $sut = PublicMetaFile::inRoot($root);

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

        $sut = PublicMetaFile::inRoot($root, '/nonexistent');

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
