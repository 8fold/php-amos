<?php
declare(strict_types=1);

namespace Eightfold\Amos\PageComponents;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\Amos\Markdown;

class PageTitle implements Buildable
{
    public static function titleForPath(string $path): string
    {
        if (file_exists($path) === false) {
            return '';
        }

        $json = file_get_contents($path);
        $obj  = json_decode($json);

        return Markdown::convertTitle($obj->title);
    }

    public static function create(
        string $publicContentRoot,
        string $contentPath
    ): PageTitle {
        return new static($publicContentRoot, $contentPath);
    }

    final private function __construct(
        private string $publicContentRoot,
        private string $contentPath
    ) {
    }

    public function build(): string
    {
        $pathParts = explode('/', $this->contentPath());
        $filtered  = array_filter($pathParts);

        $titles = [];
        while (count($filtered) > 0) {
            $path     = '/' . implode('/', $filtered) . '/';
            $metaPath = $this->publicContentRoot() . $path . '/meta.json';
            $titles[] = $this->titleFor($metaPath);

            array_pop($filtered);
        }

        $rootTitlePath = $this->publicContentRoot() . '/meta.json';
        $titles[]      = $this->titleFor($rootTitlePath);

        $titles = array_filter($titles);

        return trim(implode(' | ', $titles));
    }

    private function publicContentRoot(): string
    {
        return $this->publicContentRoot;
    }

    private function contentPath(): string
    {
        return $this->contentPath;
    }

    private function titleFor(string $path): string
    {
        return static::titleForPath($path);
    }

    private function baseTitle(): string
    {
        return $this->baseTitle;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
