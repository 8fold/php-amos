<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Site;

use SplFileInfo;

use Nyholm\Psr7\ServerRequest;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

class TestCase extends BaseTestCase
{
    protected const BASE = __DIR__ . '/test-content';

    protected const NONEXISTENT_BASE = __DIR__ . '/nonexistent';

    protected const PUBLIC_BASE = self::BASE . '/public';

    protected function root(): Root
    {
        return Root::fromString(self::BASE);
    }

    protected function nonexistentRoot(): Root
    {
        return Root::fromString(self::NONEXISTENT_BASE);
    }

    protected function publicRoot(): PublicRoot
    {
        return PublicRoot::inRoot($this->root());
    }

    protected function site(string $path = '/'): Site
    {
        return Site::init(
            $this->root(),
            $this->request($path)
        );
    }

    protected function request(string $path = '/'): ServerRequest
    {
        return new ServerRequest('get', $this->domain() . $path);
    }

    protected function domain(): string
    {
        return 'http://ex.ample';
    }
}
