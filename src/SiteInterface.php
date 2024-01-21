<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Psr\Http\Message\RequestInterface;

use Eightfold\Amos\FileSystem\Path;

use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\Http\Root as HttpRoot;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;
use Eightfold\Amos\PlainText\PublicContent;

interface SiteInterface
{
    public function domain(): HttpRoot;

    public function contentRoot(): ContentRoot;

    public function publicRoot(): PublicRoot;

    public function hasPublicMeta(Path $at): bool;

    public function publicMeta(Path $at): PublicMeta;

    public function hasPublicContent(Path $at): bool;

    public function publicContent(Path $at): PublicContent;

    /**
     * @return string[]
     */
    public function titles(Path $at): array
}
