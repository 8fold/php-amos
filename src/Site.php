<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

class Site
{
    private SplFileInfo $fileInfo;

    public static function init(string $domain, string $contentRoot): self
    {
        return new Site($domain, $contentRoot);
    }

    final private function __construct(
        private string $domain,
        private string $contentRoot
    ) {
    }

    public function domain(): string
    {
        return $this->domain;
    }

    public function contentRoot(): string
    {
        return $this->fileInfo()->getRealPath();
    }

    private function fileInfo(): SplFileInfo
    {
        if (isset($this->fileInfo) === false) {
            $this->fileInfo = new SplFileInfo($this->contentRoot);
        }
        return $this->fileInfo;
    }
}
