<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Amos\Content;

class ContentTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_content_path(): void
    {
        $contentRoot = __DIR__ . '/../content-example';
        $contentPath = $contentRoot . '/public/content.md';

        $this->assertSame(
            (new SplFileInfo($contentRoot))->getRealPath() . '/public/content.md',
            Content::init($contentRoot)->contentPath('/')
        );

        $falseRoot = __DIR__ . '/content-example';

        $this->assertSame(
            '',
            Content::init($falseRoot)->root()
        );
    }

    /**
     * @test
     */
    public function class_is_found(): void
    {
        $this->assertTrue(
            class_exists(Content::class)
        );
    }
}
