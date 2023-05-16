<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Psr\Http\Message\RequestInterface;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

interface SiteInterface
{
    public static function init(
        Root $fileSystemRoot,
        RequestInterface|false $request = false
    ): self|false;

    public function withRequest(RequestInterface $request): self;

    public function request(): RequestInterface;

    public function domain(): string;

    public function contentRoot(): Root;

    public function publicRoot(): PublicRoot;

    public function requestPath(): string;
}
