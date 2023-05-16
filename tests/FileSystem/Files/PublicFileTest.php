<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Files;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Files\PublicFile;

use Eightfold\Amos\FileSystem\Directories\Root;

class PublicFileTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_check_existence(): void
    {
        $root = Root::fromString(__DIR__ . '/../../test-content');

        $sut = PublicFile::inRoot($root, 'meta.json');

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

        $sut = PublicFile::inRoot($root, 'meta.json', '/deeper-page');

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
