<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Directories\PrivateDirectory;

use Eightfold\Amos\FileSystem\Directories\Root;

class PrivateDirectoryTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_check_existence(): void
    {
        $root = Root::fromString(__DIR__ . '/../../test-content');

        $sut = PrivateDirectory::inRoot($root, 'navigation');

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
