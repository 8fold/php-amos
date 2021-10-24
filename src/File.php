<?php

declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Markdown\Markdown;

class File
{
    /**
     * @var Store
     */
    private $store;

    private string $fileName;

    public static function create(string $absPath): static
    {
        return new static($absPath);
    }

    final public function __construct(private string $absPath)
    {
    }

    // public function withStore(Store $store)
    // {
    //     $this->store = $store;
    //     return $this;
    // }

    // public function getStore(): Store
    // {
    //     return $this->store;
    // }

    // public function withFileName(string $fileName): static
    // {
    //     $this->fileName = $fileName;
    //     return $this;
    // }

    // public function getFileName(): string
    // {
    //     return $this->fileName;
    // }

    public function isFile(): bool
    {
        return (
            file_exists($this->getAbsolutePath()) and
            is_file($this->getAbsolutePath())
        );
    }

    public function getContent(): string
    {
        $c = file_get_contents($this->getAbsolutePath());
        if (is_bool($c)) {
            return '';
        }
        return $c;
    }

    private function getAbsolutePath(): string
    {
        return $this->absPath;
    }

    public function title(): string
    {
        $meta = Markdown::create()->getFrontMatter($this->getContent());
        if (count($meta) === 0 or ! array_key_exists('title', $meta)) {
            return '';
        }
        return $meta['title'];
    }
}
