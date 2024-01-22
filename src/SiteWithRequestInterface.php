<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Psr\Http\Message\RequestInterface;

use Eightfold\Amos\SiteInterface;

interface SiteWithRequestInterface extends SiteInterface
{
    public function request(): RequestInterface;
}
