<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Amos\SiteInterface;

use SplFileInfo;
use StdClass;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\FileSystem\Directories\PublicRoot;

use Eightfold\Amos\PlainText\Content;

class Site implements SiteInterface
{
    private Root $file_system_root;

    private PublicRoot $file_system_public_root;

    private string $domain;

    private UriInterface $uri;

    private string $request_path;

    public static function init(
        Root $fileSystemRoot,
        RequestInterface $request,
        LoggerInterface|false $logger = false
    ): self|false {
        if ($fileSystemRoot->notFound()) {
            return false;
        }
        return new self($fileSystemRoot, $request, $logger);
    }

    final private function __construct(
        private readonly Root $fileSystemRoot,
        private readonly RequestInterface $request,
        private readonly LoggerInterface|false $logger
    ) {
    }

    public function domain(): string
    {
        if (isset($this->domain) === false) {
            $this->domain = $this->uri()->getScheme() . '://' .
                $this->uri()->getAuthority();
        }
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

    public function request(): RequestInterface
    {
        return $this->request;
    }

    private function uri(): UriInterface
    {
        if (isset($this->uri) === false) {
            $this->uri = $this->request()->getUri();
        }
        return $this->uri;
    }

    public function requestPath(): string
    {
        if (isset($this->request_path) === false) {
            $this->request_path = $this->uri()->getPath();
        }
        return $this->request_path;
    }

    public function logger(): LoggerInterface|false
    {
        return $this->logger;
    }
}
