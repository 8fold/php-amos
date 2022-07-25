<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;

use Eightfold\Amos\Markdown;

class Content
{
    private SplFileInfo $fileInfo;

    public static function init(string $path): self
    {
        return new self($path);
    }

    final private function __construct(private string $path)
    {
    }

    public function root(): string
    {
        $path = $this->fileInfo()->getRealPath();
        if ($path === false) {
            return '';
        }
        return $path;
    }

    public function publicContentRoot(): string
    {
        return $this->root() . '/public';
    }

    public function publicPath(string $at): string
    {
        if ($at === '/') {
            $at = '';
        }
        return $this->publicContentRoot() . $at;
    }

    public function contentPath(string $at): string
    {
        if ($at === '/') {
            $at = '';
        }
        return $this->publicPath($at) . '/content.md';
    }

    public function found(string $at): bool
    {
        return file_exists($this->publicContentRoot($at));
    }

    public function notFound(string $at): bool
    {
        return ! $this->found($at);
    }

    public function convertedContent(string $at): string
    {
        return Markdown::convert(
            markdown: file_get_contents(
                $this->contentPath(at: $at)
            )
        );
    }

    private function fileInfo(): SplFileInfo
    {
        if (isset($this->fileInfo) === false) {
            $this->fileInfo = new SplFileInfo($this->path);
        }
        return $this->fileInfo;
    }
}
