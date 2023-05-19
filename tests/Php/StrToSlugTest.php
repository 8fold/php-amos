<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Php;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\Php\StrToSlug;

class StrToSlugTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_convert_string_to_slug(): void
    {
        $base = 'Hello, World!';

        $expected = 'hello-world';

        $result = StrToSlug::fromString($base);

        $this->assertSame($expected, $result);

        $obj = new StrToSlug();

        $result = $obj($base);

        $this->assertSame($expected, $result);
    }
}
