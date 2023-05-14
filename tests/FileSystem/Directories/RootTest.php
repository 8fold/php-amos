<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem\Directories;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Directories\Root;

class RootTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_check_existence(): void
    {
        $sut = Root::fromString(__DIR__ . '/../../test-content');

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

        $sut = Root::fromString(__DIR__ . '/../nonexistent');

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
