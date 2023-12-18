<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\PlainText;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\PlainText\PublicContent;

class PublicContentTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_get_content(): void
    {
        $sut = PublicContent::inRoot(parent::root());

        $expected = <<<md
        # Root test content

        md;

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $sut = PublicContent::inRoot(parent::root(), '/l1-page');

        $result = $sut->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }
}
