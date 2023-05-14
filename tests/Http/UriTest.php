<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Http;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Http\Uri;

use Eightfold\Amos\Http\ServerRequestGet;

class UriTest extends BaseTestCase
{
    /**
     * @test
     * @group oop
     */
    public function can_initialize(): void
    {
        $request = ServerRequestGet::usingDefault();

        $sut = Uri::fromPsr7($request);

        $this->assertNotNull(
            $sut
        );
    }
}
