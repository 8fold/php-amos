<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Site;

use SplFileInfo;

use Nyholm\Psr7\ServerRequest;

class TestCase extends BaseTestCase
{
    protected function domain(): string
    {
        return 'http://ex.ample';
    }

    protected function contentRoot(): string
    {
        $content_root = __DIR__ . '/test-content';
        return $content_root;
    }

    protected function request(string $path = '/'): ServerRequest
    {
        return new ServerRequest('get', $this->domain() . $path);
    }

    protected function site(string $path = '/'): Site
    {
        return Site::init(
            $this->contentRoot(),
            $this->request($path)
        );
    }
}
