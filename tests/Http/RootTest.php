<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Http;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\Http\Root;

class RootTest extends BaseTestCase
{
    /**
     * @test
     */
    public function can_accept_valid_uri(): void
    {
        $sut = Root::fromString(
            'http://userinfo:password@ex.ample:1111/path?u=not#fragment'
        );

        $this->assertTrue(
            $sut->isValid()
        );

        $expected = 'http://ex.ample:1111';

        $this->assertSame(
            $expected,
            $sut->toString()
        );

        $sut = Root::fromRequest(
            parent::request()
        );

        $expected = parent::domain()->toString();

        $this->assertSame(
            $expected,
            $sut->toString()
        );
    }
}
