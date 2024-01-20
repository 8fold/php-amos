<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use function Eightfold\Amos\titles_for_meta_in_public_dir;

use Eightfold\Amos\FileSystem\Path;

class SiteTest extends TestCase
{
    /**
     * @test
     */
    public function has_public_content(): void
    {
        $result = $this->site()->hasPublicContent(
            Path::fromString()
        );

        $this->assertTrue($result);

        $expected = <<<md
        # Root test content

        md;

        $result = $this->site()->publicContent(
            Path::fromString()
        )->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function has_titles(): void
    {
        $expected = [
            'L1 page',
            'Root test content'
        ];

        $result = $this->site()->titles(
            Path::fromString('l1-page')
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function has_expected_link_stack(): void
    {
        $expected = [
            '/l1-page/l2-page/' => 'L2 page',
            '/l1-page/' => 'L1 page',
            '/' => 'Root test content'
        ];

        $result = $this->site()->linkStack(
            Path::fromString(
                DIRECTORY_SEPARATOR . 'l1-page' .
                DIRECTORY_SEPARATOR . 'l2-page'
            )
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_build_breadcrumbs_l2(): void
    {
        $expected = [
            '/' => 'Root test content',
            '/l1-page/' => 'L1 page',
            '/l1-page/l2-page/' => 'L2 page'
        ];

        $result = $this->site()->breadcrumbs(
            Path::fromString(
                DIRECTORY_SEPARATOR . 'l1-page' .
                DIRECTORY_SEPARATOR . 'l2-page'
            )
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_build_breadcrumbs_l4(): void
    {
        $expected = [
            '/' => 'Root test content',
            '/l1-page/'                         => 'L1 page',
            '/l1-page/l2-page/'                 => 'L2 page',
            '/l1-page/l2-page/l3-page/'         => 'L3 page',
            '/l1-page/l2-page/l3-page/l4-page/' => 'L4 page'
        ];

        $result = $this->site()->breadcrumbs(
            Path::fromString('/l1-page/l2-page/l3-page/l4-page')
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_build_breadcrumbs_can_exclude_levels(): void
    {
        $expected = [
            // '/' => 'Root test content',
            '/l1-page/'                         => 'L1 page',
            '/l1-page/l2-page/'                 => 'L2 page',
            '/l1-page/l2-page/l3-page/'         => 'L3 page',
            '/l1-page/l2-page/l3-page/l4-page/' => 'L4 page'
        ];

        $result = $this->site()->breadcrumbs(
            Path::fromString('/l1-page/l2-page/l3-page/l4-page'),
            1
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = [
            // '/' => 'Root test content',
            // '/l1-page/'                         => 'L1 page',
            '/l1-page/l2-page/'                 => 'L2 page',
            '/l1-page/l2-page/l3-page/'         => 'L3 page',
            // '/l1-page/l2-page/l3-page/l4-page/' => 'L4 page'
        ];

        $result = $this->site()->breadcrumbs(
            Path::fromString(
                DIRECTORY_SEPARATOR . 'l1-page' .
                DIRECTORY_SEPARATOR . 'l2-page' .
                DIRECTORY_SEPARATOR . 'l3-page' .
                DIRECTORY_SEPARATOR . 'l4-page'
            ),
            2,
            2
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function has_public_meta(): void
    {
        $result = $this->site()->hasPublicMeta(
            Path::fromString()
        );

        $this->assertTrue($result);

        $result = $this->site()->publicMeta(
            Path::fromString()
        );

        $expected = "Root test content";

        $this->assertEquals(
            $expected,
            $result->title()
        );

        $result = $this->site()->hasPublicMeta(
            Path::fromString('nonexistent')
        );

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function has_expected_domain(): void
    {
        $expected = $this->domain()->toString();

        $result = $this->site()->domain()->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
