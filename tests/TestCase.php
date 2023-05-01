<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Eightfold\Amos\Site;

use SplFileInfo;

class TestCase extends BaseTestCase
{
    protected function site(): Site
    {
        $realPath = (new SplFileInfo(__DIR__ . '/test-content'))
            ->getRealPath();

        return Site::init(
            'http://ex.ample',
            $realPath
        );
    }
}
