<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Psr\Http\Message\RequestInterface;

use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\Http\Root as HttpRoot;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;
use Eightfold\Amos\PlainText\PublicContent;

interface SiteInterface
{
    public static function init(
        ContentRoot $fileSystemRoot,
        HttpRoot $domain
    ): self|false;

    public function domain(): HttpRoot;

    public function contentRoot(): ContentRoot;

    public function publicRoot(): PublicRoot;

    public function hasPublicMeta(string $at = ''): bool;

    public function publicMeta(string $at = '/'): PublicMeta;

    public function hasPublicContent(string $at = '/'): bool;

    public function publicContent(string $at = '/'): PublicContent;
}
