<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\PlainText\PublicContent;

use Eightfold\Amos\FileSystem\Directories\Root;

class PublicContentTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_get_content(): void
    {
        $root = Root::fromString(__DIR__ . '/../test-content');

        $sut = PublicContent::inRoot($root);

        $expected = <<<md
        # Root test content

        md;

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $sut = PublicContent::inRoot($root, '/deeper-page');

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
