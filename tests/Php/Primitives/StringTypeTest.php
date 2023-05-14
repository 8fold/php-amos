<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Php\Primitives;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Php\Primitives\StringType;

class StringTypeTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_initialize(): void
    {
        $expected = 'Hello!';

        $sut = StringType::fromString($expected);

        $result = (string) $sut;

        $this->assertSame(
            $expected,
            $result
        );
    }
}
