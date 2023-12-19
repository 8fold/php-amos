<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use function Eightfold\Amos\titles_for_meta_in_public_dir;

class SiteTest extends TestCase
{
    /**
     * @test
     */
    public function has_public_content(): void
    {
        $result = $this->site()->hasPublicContent();

        $this->assertTrue($result);

        $expected = <<<md
        # Root test content

        md;

        $result = $this->site()->publicContent()->toString();

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

        $result = $this->site()->titles('/l1-page');

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

        $result = $this->site()->linkStack('/l1-page/l2-page');

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

        $result = $this->site()->breadcrumbs('/l1-page/l2-page');

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

        $result = $this->site()->breadcrumbs('/l1-page/l2-page/l3-page/l4-page');

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

        $result = $this->site()->breadcrumbs('/l1-page/l2-page/l3-page/l4-page', 1);

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

        $result = $this->site()->breadcrumbs('/l1-page/l2-page/l3-page/l4-page', 2, 2);

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
        $result = $this->site()->hasPublicMeta();

        $this->assertTrue($result);

        $result = $this->site()->hasPublicMeta('/nonexistent');

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
