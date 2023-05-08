<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

interface SiteInterface
{
    public static function init(
        SplFileInfo|string $contentIn,
        ServerRequestInterface $request
    ): self|false;

    public function domain(): string;

    public function contentRoot(): string;

    public function requestPath(): string;

    public function logger(): LoggerInterface|false;
}
