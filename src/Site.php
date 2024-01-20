<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Psr\Http\Message\UriInterface;

use Eightfold\Amos\SiteInterface;

use Eightfold\Amos\FileSystem\Path;

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

    public function hasPublicMeta(Path $at): bool
    {
        return $this->publicMeta($at)->toBool();
    }

    public function publicMeta(Path $at): PublicMeta
    {
        $key = $at->toString();

        if (array_key_exists($key, $this->public_metas)) {
            return $this->public_metas[$key];
        }

        $meta = PublicMeta::inRoot($this->contentRoot(), $at);
        $this->public_metas[$key] = $meta; // TODO: Make a custom collection

        return $meta;
    }

    // TODO: Recurring question will be whether "at" separator
    //       is URI (known) or file system (unknown)
    public function hasPublicContent(Path $at): bool
    {
        if (is_string($at)) {
            $at = Path::fromString($at);
        }
        return $this->publicContent($at)->toBool();
    }

    public function publicContent(Path $at): PublicContent
    {
        $key = $at->toString();

        if (array_key_exists($key, $this->publicContents)) {
            return $this->publicContents[$key];
        }

        $content = PublicContent::inRoot($this->contentRoot(), $at);
        $this->publicContents[$key] = $content; // TODO: convert to custom collection

        return $content;
    }

    // TODO: Convert to using Filename
    public function publicFile(string $filename, Path $at): PublicFile
    {
        if (is_string($at)) {
            $at = Path::fromString($at);
        }
        return PublicFile::inRoot($this->contentRoot(), $filename, $at);
    }


    /**
     * @return string[]
     */
    public function titles(Path $at): array
    {
        return array_values(
            $this->linkStack($at)
        );
    }

    /**
     * @return array<string, string>
     */
    public function breadcrumbs(
        Path $at,
        int $offset = 0,
        int|false $length = false
    ): array {
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
    public function linkStack(Path $at): array
    {
        $parts = $at->parts();

        $stack = [];
        while (count($parts) > 0) {
            $key         = '/' . implode('/', $parts) . '/';
            $this->updateStack($key, $stack);

            array_pop($parts);
        }

        $this->updateStack('/', $stack);

        return array_filter($stack);
    }

    /**
     * @param array<string, string> $stack
     */
    private function updateStack(string $key, array &$stack): void
    {
        $uriPath     = Path::fromString($key);
        $stack[$key] = $this->publicMeta(at: $uriPath)->title();
    }
}
