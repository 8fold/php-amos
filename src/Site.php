<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Amos\SiteInterface;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;
use Eightfold\Amos\PlainText\PublicContent;

class Site implements SiteInterface
{
    private Root $file_system_root;

    private PublicRoot $file_system_public_root;

    private array $publicMetas = [];

    private array $publicContents = [];

    public static function init(
        Root $fileSystemRoot,
        string $domain
    ): self|false {
        if ($fileSystemRoot->notFound()) {
            return false;
        }
        return new self($fileSystemRoot, $domain);
    }

    final private function __construct(
        private readonly Root $fileSystemRoot,
        private readonly string $domain
    ) {
    }

    public function domain(): string
    {
        return $this->domain;
    }

    public function contentRoot(): Root
    {
        if (isset($this->file_system_root) === false) {
            $this->file_system_root = $this->fileSystemRoot;
        }
        return $this->file_system_root;
    }

    public function publicRoot(): PublicRoot
    {
        if (isset($this->file_system_public_root) === false) {
            $this->file_system_public_root = PublicRoot::inRoot(
                $this->contentRoot()
            );
        }
        return $this->file_system_public_root;
    }

    public function hasPublicMeta(string $at = ''): bool
    {
        return $this->publicMeta($at)->toBool();
    }

    public function publicMeta(string $at = '/'): PublicMeta
    {
        if (array_key_exists($at, $this->publicMetas)) {
            return $this->publicMetas[$at];
        }

        $meta = PublicMeta::inRoot($this->contentRoot(), $at);
        $this->publicMetas[$at] = $meta;

        return $meta;
    }

    public function hasPublicContent(string $at = '/'): bool
    {
        return $this->publicContent($at)->toBool();
    }

    public function publicContent(string $at = '/'): PublicContent
    {
        if (array_key_exists($at, $this->publicContents)) {
            return $this->publicContents[$at];
        }

        $content = PublicContent::inRoot($this->contentRoot(), $at);
        $this->publicContents[$at] = $content;

        return $content;
    }

    public function titles(string $at = ''): array
    {
        $pathParts = explode('/', $at);
        $filtered  = array_filter($pathParts);

        $titles = [];
        while (count($filtered) > 0) {
            $path = '/' . implode('/', $filtered) . '/';
            $titles[] = $this->publicMeta(at: $path)->title();

            array_pop($filtered);
        }

        $titles[] = $this->publicMeta(at: '/')->title();

        return array_filter($titles);
    }
}
