<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Amos\SiteInterface;

use Eightfold\Amos\FileSystem\PathFromRoot;

use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\Http\Root as HttpRoot;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;
use Eightfold\Amos\PlainText\PublicContent;
use Eightfold\Amos\PlainText\PublicFile;

class Site implements SiteInterface
{
    private ContentRoot $file_system_root;

    private PublicRoot $file_system_public_root;

    /**
     * @var array<string, PublicMeta>
     */
    private array $public_metas = [];

    /**
     * @var array<string, PublicContent>
     */
    private array $publicContents = [];

    public static function init(
        ContentRoot $fileSystemRoot,
        HttpRoot $domain
    ): self|false {
        if ($fileSystemRoot->notFound()) {
            return false;
        }
        return new self($fileSystemRoot, $domain);
    }

    final private function __construct(
        private readonly ContentRoot $fileSystemRoot,
        private readonly HttpRoot $domain
    ) {
    }

    public function domain(): HttpRoot
    {
        return $this->domain;
    }

    public function contentRoot(): ContentRoot
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
        $at = PathFromRoot::fromString($at)->toString();
        return $this->publicMeta($at)->toBool();
    }

    public function publicMeta(string $at = ''): PublicMeta
    {
        $at = PathFromRoot::fromString($at)->toString();
        if (array_key_exists($at, $this->public_metas)) {
            return $this->public_metas[$at];
        }

        $meta = PublicMeta::inRoot($this->contentRoot(), $at);
        $this->public_metas[$at] = $meta;

        return $meta;
    }

    // TODO: Recurring question will be whether "at" separator is URI (known) or file system (unknown)
    public function hasPublicContent(string $at = '/'): bool
    {
        $at = PathFromRoot::fromString($at)->toString();
        return $this->publicContent($at)->toBool();
    }

    public function publicContent(string $at = '/'): PublicContent
    {
        $at = PathFromRoot::fromString($at)->toString();
        if (array_key_exists($at, $this->publicContents)) {
            return $this->publicContents[$at];
        }

        $content = PublicContent::inRoot($this->contentRoot(), $at);
        $this->publicContents[$at] = $content;

        return $content;
    }

    public function publicFile(string $filename, string $at = '/'): PublicFile
    {
        $at = PathFromRoot::fromString($at)->toString();
        return PublicFile::inRoot($this->contentRoot(), $filename, $at);
    }


    /**
     * @return string[]
     */
    public function titles(string $at = ''): array
    {
        $at = PathFromRoot::fromString($at)->toString();
        return array_values(
            $this->linkStack($at)
        );
    }

    /**
     * @return array<string, string>
     */
    public function breadcrumbs(
        string $at = '',
        int $offset = 0,
        int|false $length = false
    ): array {
        $at = PathFromRoot::fromString($at)->toString();
        $sorted = array_reverse(
            $this->linkStack($at)
        );

        if ($length === false) {
            $length = null;
        }

        return array_slice($sorted, $offset, $length);
    }

    /**
     * @return array<string, string>
     */
    public function linkStack(string $at = ''): array
    {
        $at = PathFromRoot::fromString($at)->toString();
        $pathParts = explode('/', $at);
        $filtered  = array_filter($pathParts);

        $stack = [];
        while (count($filtered) > 0) {
            $path = '/' . implode('/', $filtered) . '/';
            $stack[$path] = $this->publicMeta(at: $path)->title();

            array_pop($filtered);
        }

        $stack['/'] = $this->publicMeta(at: '/')->title();

        return array_filter($stack);
    }
}
