<?php

declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Markdown\Markdown as EFMarkdown;

class Markdown
{
    /**
     * @var EFMarkdown
     */
    private $efMarkdown;

    private string $markdown = '';

    /**
     * @var array<mixed>
     */
    private array $frontMatter = [];

    private string $content = '';

    public static function create(string $markdown): Markdown
    {
        return new Markdown($markdown);
    }

    public function __construct(string $markdown)
    {
        $this->markdown = $markdown;
    }

    public function title(): string
    {
        $f = $this->frontMatter();
        if (array_key_exists('title', $f)) {
            return $f['title'];
        }
        return '';
    }

    /**
     * @return array<mixed>
     */
    public function frontMatter(): array
    {
        if (count($this->frontMatter) === 0) {
            $this->frontMatter = $this->fluentMarkdown()->frontMatter();
        }
        return $this->frontMatter;
    }

    public function content(): string
    {
        if (strlen($this->content) === 0) {
            $this->content = $this->fluentMarkdown()->content();
        }
        return $this->content;
    }

    public function html(): string
    {
        return $this->fluentMarkdown()->convertedContent();
    }

    private function fluentMarkdown(): EFMarkdown
    {
        if ($this->efMarkdown === null) {
            $this->efMarkdown = EFMarkdown::create($this->markdown);
        }
        return $this->efMarkdown;
    }
}
