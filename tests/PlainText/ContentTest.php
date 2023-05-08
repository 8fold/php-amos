<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\ObjectsFromJson;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\PlainText\Content;

use StdClass;

class ContentTest extends TestCase
{
    /**
     * @test
     * @group focus
     */
    public function can_get_partials_in_content(): void
    {
        $singleLine = '{!! date:created=2023-01-01 !!}';

        $content = <<<md
        # Hello

        $singleLine
        md;

        $expected = new StdClass();
        $expected->to_replace = [
            $singleLine
        ];

        $toUse                = new StdClass();
        $toUse->pattern       = 'date:created=2023-01-01';
        $toUse->reference     = 'date';
        $toUse->args          = new StdClass();
        $toUse->args->created = '2023-01-01';
        $expected->to_use = [$toUse];

        $result = Content::partialsInContent($content);

        $this->assertEquals(
            $expected,
            $result
        );

        $multiline = <<<string
        {!! date:
            created=2023-01-01
        !!}
        string;

        $content = <<<md
        # Hello

        $multiline
        md;

        $expected->to_replace = [$multiline];

        $toUse                = new StdClass();
        $toUse->pattern       = "date:\n    created=2023-01-01";
        $toUse->reference     = 'date';
        $toUse->args          = new StdClass();
        $toUse->args->created = '2023-01-01';
        $expected->to_use = [
            $toUse
        ];

        $result = Content::partialsInContent($content);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_template_name_and_args_content(): void
    {
        $content = 'date';

        $expected            = new StdClass();
        $expected->pattern   = $content;
        $expected->reference = $content;
        $expected->args      = new StdClass();

        $result = Content::partialWithArgs($content);

        $this->assertEquals(
            $expected,
            $result
        );

        $content = ' date ';

        $expected            = new StdClass();
        $expected->pattern   = 'date';
        $expected->reference = 'date';
        $expected->args      = new StdClass();

        $result = Content::partialWithArgs($content);

        $this->assertEquals(
            $expected,
            $result
        );

        $content = 'date:day=2023-05-07,unused=true';

        $expected               = new StdClass();
        $expected->pattern      = $content;
        $expected->reference    = 'date';
        $expected->args         = new StdClass();
        $expected->args->day    = '2023-05-07';
        $expected->args->unused = 'true';

        $result = Content::partialWithArgs($content);

        $this->assertEquals(
            $expected,
            $result
        );

        $content = <<<string
        date:
            updated=2023-05-07,
            created=2023-01-01
        string;

        $expected                = new StdClass();
        $expected->pattern       = $content;
        $expected->reference     = 'date';
        $expected->args          = new StdClass();
        $expected->args->updated = '2023-05-07';
        $expected->args->created = '2023-01-01';

        $result = Content::partialWithArgs($content);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_replace_string_with_string(): void
    {
        $content = <<<md
        # Hello

        {!! date !!}
        md;

        $expected = <<<md
        # Hello

        2023-05-07
        md;

        $result = Content::replace('{!! date !!}', '2023-05-07', $content);

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     * @group focus
     */
    public function can_get_content_using_site(): void
    {
        $expected = <<<md
        # Hello

        md;

        $result = Content::fromSite($this->site(), '/navigation');

        $this->assertEquals(
            $expected,
            $result
        );

        $expected = <<<md
        # Root test content

        md;

        $result = Content::inPublicFileFromSite($this->site());

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
