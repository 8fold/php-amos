<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

interface SiteInterface
{
    public static function init(
        Root $fileSystemRoot,
        RequestInterface $request,
        LoggerInterface|false $logger = false
    ): self|false;

    public function domain(): string;

    public function contentRoot(): Root;

    public function publicRoot(): PublicRoot;

    public function requestPath(): string;

    public function logger(): LoggerInterface|false;
}
