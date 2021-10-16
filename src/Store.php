<?php

declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\FileSystem\Item;

class Store
{
    private string $root = '/';

    private string $path = '';

    public static function create(string $root, string $path = ''): Store
    {
        return new Store($root, $path);
    }

    public function __construct(string $root, string $path = '')
    {
        $this->root = $root;
        $this->path = $path;
    }

    public function root(): string
    {
        return $this->root;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function isRoot(): bool
    {
        return count($this->tail()) === 0;
    }

    public function hasFile(string $fileName): bool
    {
        return is_object($this->file($fileName));
    }

    /**
     * @return Item|bool|boolean           [description]
     */
    public function file(string $fileName)
    {
        $item = $this->item($fileName);
        if ($item->isFile()) {
            return $item;
        }
        return false;
    }

    /**
     * @return Markdown|bool|boolean [description]
     */
    public function markdown(string $fileName = 'content.md')
    {
        $item = $this->item($fileName);
        if ($item->isFile()) {
            $c = $item->content();
            if (is_string($c)) {
                return Markdown::create($c);
            }
        }
        return false;
    }

    public function append(string ...$parts): Store
    {
        return Store::create(
            $this->root,
            implode('/', $this->extendedTail(...$parts))
        );
    }

    public function up(): Store
    {
        $tail = $this->tail();
        array_pop($tail);
        $path = implode('/', $tail);
        return Store::create($this->root, '/' . $path);
    }

    public function media(string $path): Store
    {
        return Store::create($this->root, '/.media' . $path);
    }

    public function assets(string $path): Store
    {
        return Store::create($this->root, '/.assets' . $path);
    }

    /**
     * @param  string $fileName [description]
     * @return array<string>           [description]
     */
    public function navigation(string $fileName = 'main.md'): array
    {
        $m = Store::create($this->root, '/.navigation')->markdown($fileName);
        if (is_object($m)) {
            $meta = $m->frontMatter();
            if (array_key_exists('navigation', $meta)) {
                return $meta['navigation'];
            }
        }
        return [];
    }

    /**
     * @param  string $folder [description]
     * @return array<Item>         [description]
     */
    public function folderContent(string $folder): array
    {
        $item = Item::create($this->root)->append($folder);
        if ($item->isFolder()) {
            $c = $item->content();
            if (is_array($c)) {
                return $c;

            }
        }
        return [];
    }

    private function item(string ...$append): Item
    {
        $extendedTail = $this->extendedTail(...$append);
        return Item::create($this->root)->append(...$extendedTail);
    }

    /**
     * @return array<string>         [description]
     */
    private function extendedTail(string ...$append): array
    {
        return array_merge($this->tail(), $append);
    }

    /**
     * @return array<string> [description]
     */
    private function tail(): array
    {
        $tail   = explode('/', $this->path());
        $tail   = array_filter($tail);
        return $tail;
    }



/***********/

    private function banners(): Item
    {
        return Item::create($this->root)->append('.banners');
    }
}
