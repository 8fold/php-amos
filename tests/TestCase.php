<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Site;

use SplFileInfo;

use Nyholm\Psr7\ServerRequest;

use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\Http\Root as HttpRoot;

class TestCase extends BaseTestCase
{
    protected const BASE = __DIR__ . DIRECTORY_SEPARATOR . 'test-content';

    protected const NONEXISTENT_BASE = __DIR__ . DIRECTORY_SEPARATOR . 'nonexistent';

    protected const PUBLIC_BASE = self::BASE . DIRECTORY_SEPARATOR . 'public';

    protected function root(): ContentRoot
    {
        return ContentRoot::fromString(self::BASE);
    }

    protected function nonexistentRoot(): ContentRoot
    {
        return ContentRoot::fromString(self::NONEXISTENT_BASE);
    }

    protected function publicRoot(): PublicRoot
    {
        return PublicRoot::inRoot($this->root());
    }

    protected function site(string $path = '/'): Site
    {
        return Site::init(
            $this->root(),
            $this->domain()
        );
    }

    protected function request(string $path = '/'): ServerRequest
    {
        return new ServerRequest('get', $this->domain() . $path);
    }

    protected function domain(): HttpRoot
    {
        return HttpRoot::fromString('http://ex.ample');
    }
}
