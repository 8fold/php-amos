<?php
declare(strict_types=1);

namespace Eightfold\Amos\Documents;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\HTMLBuilder\Document;
use Eightfold\HTMLBuilder\Element;

use Eightfold\Amos\PageComponents\PageTitle;

class Page implements Buildable
{
    public static function create(
        string $publicContentRoot,
        string $contentPath,
        string $body
    ): self
    {
        return new self($publicContentRoot, $contentPath, $body);
    }

    final private function __construct(
        private string $publicContentRoot,
        private string $contentPath,
        private string $body
    )
    {
    }

    private function publicContentRoot(): string
    {
        return $this->publicContentRoot;
    }

    private function contentPath(): string
    {
        return $this->contentPath;
    }

    private function body(): string
    {
        return $this->body;
    }

    public function build(): string
    {
        return Document::create(
            PageTitle::create($this->publicContentRoot(), $this->contentPath())
                ->build()
        )->head(

        )->body(
            $this->body()
        )->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
