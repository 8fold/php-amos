<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use function Eightfold\Amos\titles_for_meta_in_public_dir;

use Eightfold\Amos\FileSystem\Path;

class SiteWithRequestTest extends TestCase
{
    /**
     * @test
     */
    public function has_public_content(): void
    {
        $result = parent::siteWithRequest()->hasPublicContent();

        $this->assertTrue($result);

        $expected = <<<md
        # Root test content

        md;

        $result = parent::siteWithRequest()->publicContent()->toString();

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

        $result = parent::siteWithRequest('/l1-page')->titles();

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

        $result = parent::siteWithRequest('/l1-page/l2-page/')
            ->linkStack();

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

        $result = parent::siteWithRequest('/l1-page/l2-page')
            ->breadcrumbs();

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

        $result = parent::siteWithRequest('/l1-page/l2-page/l3-page/l4-page')
            ->breadcrumbs();

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

        $result = parent::siteWithRequest('/l1-page/l2-page/l3-page/l4-page')
            ->breadcrumbs(offset: 1);

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

        $result = parent::siteWithRequest('/l1-page/l2-page/l3-page/l4-page')
            ->breadcrumbs(offset: 2, length: 2);

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
        $result = parent::siteWithRequest()->hasPublicMeta();

        $this->assertTrue($result);

        $result = parent::siteWithRequest()->publicMeta()->title();

        $expected = "Root test content";

        $this->assertEquals(
            $expected,
            $result
        );

        $result = parent::siteWithRequest()->hasPublicMeta(
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

        $result = parent::siteWithRequest()->domain()->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
