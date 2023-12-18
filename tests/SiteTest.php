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
            'Deeper page',
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
