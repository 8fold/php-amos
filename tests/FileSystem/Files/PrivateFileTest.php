<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Files;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Files\PrivateFile;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PrivateDirectory;

class PrivateFileTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_check_existence(): void
    {
        $root = Root::fromString(__DIR__ . '/../../test-content');

        $sut = PrivateFile::inRoot($root, 'meta.json', '/navigation/');

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
