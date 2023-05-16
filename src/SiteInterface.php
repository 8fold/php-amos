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
        string $domain
    ): self|false;

    public function domain(): string;

    public function contentRoot(): Root;

    public function publicRoot(): PublicRoot;

    public function hasPublicMeta(string $at = ''): bool;

    public function publicMeta(string $at = '/'): PublicMeta;

    public function hasPublicContent(string $at = '/'): bool;

    public function publicContent(string $at = '/'): PublicContent;
}
