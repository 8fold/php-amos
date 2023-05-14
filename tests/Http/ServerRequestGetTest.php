<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Http;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Http\ServerRequestGet;

class ServerRequestGetTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_initialize(): void
    {
        $sut = ServerRequestGet::usingDefault();

        $this->assertNotNull(
            $sut
        );
    }
}
