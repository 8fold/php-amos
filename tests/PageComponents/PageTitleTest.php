<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Amos\PageComponents\PageTitle;

use Eightfold\Amos\Site;

use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;

class PageTitleTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_root(): void
    {
        $_SERVER['REQUEST_URI'] = '/';

        $psr17Factory = new Psr17Factory();

        $request = (new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        ))->fromGlobals();

        $site = Site::init(
            withDomain: 'http://php.amos',
            contentIn: __DIR__ . '/../../content-example'
        );

        $site->response(for: $request);

        $this->assertSame(
            '8fold Amos',
            PageTitle::create($site)->build()
        );
    }

    /**
     * @test
     */
    public function class_is_found(): void
    {
        $this->assertTrue(
            class_exists(PageTitle::class)
        );
    }
}
