<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

use Eightfold\Amos\SiteInterface;

use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;

use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\Http\Root as HttpRoot;

use Eightfold\Amos\ObjectsFromJson\PublicMeta;
use Eightfold\Amos\PlainText\PublicContent;
use Eightfold\Amos\PlainText\PublicFile;

class Site implements SiteInterface
{
    private PublicRoot $fileSystemPublicRoot;

    private RequestInterface|false $request = false;

    /**
     * @var array<string, PublicMeta>
     */
    private array $public_metas = [];

    /**
     * @var array<string, PublicContent>
     */
    private array $publicContents = [];

    public static function initWithRequest(
        ContentRoot $fileSystemRoot,
        HttpRoot $domain,
        RequestInterface $request
    ): self|false {
        $self = self::init($fileSystemRoot, $domain);
        if ($self === false) {
            return false;
        }
        return $self->withRequest($request);
    }

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

    public function request(): RequestInterface|false
    {
        return $this->request;
    }

    public function withRequest(RequestInterface $request): self
    {
        $this->request = $request;
        return $this;
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

    public function publicFile(Filename $filename, Path $at): PublicFile
    {
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
