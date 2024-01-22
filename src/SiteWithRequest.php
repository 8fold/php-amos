<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

use Eightfold\Amos\SiteWithRequestInterface;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;

use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\Http\Root as HttpRoot;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;
use Eightfold\Amos\PlainText\PublicContent;
use Eightfold\Amos\PlainText\PublicFile;

class SiteWithRequest implements SiteWithRequestInterface
{
    private HttpRoot $domain;

    private PublicRoot $fileSystemPublicRoot;

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
        RequestInterface $request
    ): self|false {
        return new self($fileSystemRoot, $request);
    }

    final private function __construct(
        private readonly ContentRoot $fileSystemRoot,
        private readonly RequestInterface $request
    ) {
    }

    public function contentRoot(): ContentRoot
    {
        return $this->fileSystemRoot;
    }

    public function publicRoot(): PublicRoot
    {
        if (isset($this->fileSystemPublicRoot) === false) {
            $this->fileSystemPublicRoot = PublicRoot::inRoot(
                $this->contentRoot()
            );
        }
        return $this->fileSystemPublicRoot;
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }

    public function domain(): HttpRoot
    {
        if (isset($this->domain) === false) {
            $this->domain = HttpRoot::fromRequest($this->request());
        }
        return $this->domain;
    }

    public function hasPublicMeta(Path|false $at = false): bool
    {
        if ($at === false) {
            return $this->publicMeta($at)->toBool();
        }
        return $this->publicMeta($at)->toBool();
    }

    public function publicMeta(Path|false $at = false): PublicMeta
    {
        $at = $this->path($at);

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
    public function hasPublicContent(Path|false $at = false): bool
    {
        $at = $this->path($at);

        return $this->publicContent($at)->toBool();
    }

    public function publicContent(Path|false $at = false): PublicContent
    {
        $at = $this->path($at);

        $key = $at->toString();

        if (array_key_exists($key, $this->publicContents)) {
            return $this->publicContents[$key];
        }

        $content = PublicContent::inRoot($this->contentRoot(), $at);
        $this->publicContents[$key] = $content; // TODO: convert to custom collection

        return $content;
    }

    public function publicFile(
        Filename $filename,
        Path|false $at = false
    ): PublicFile {
        $at = $this->path($at);

        return PublicFile::inRoot($this->contentRoot(), $filename, $at);
    }

    /**
     * @return string[]
     */
    public function titles(Path|false $at = false): array
    {
        $at = $this->path($at);

        return array_values(
            $this->linkStack($at)
        );
    }

    /**
     * @return array<string, string>
     */
    public function breadcrumbs(
        Path|false $at = false,
        int $offset = 0,
        int|false $length = false
    ): array {
        $at = $this->path($at);

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
    public function linkStack(Path|false $at = false): array
    {
        $at = $this->path($at);

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

    private function path(Path|false $at = false): Path
    {
        if ($at === false) {
            return Path::fromString(
                $this->request()->getUri()->getPath()
            );
        }
        return $at;
    }
}
