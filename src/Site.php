<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Amos\SiteInterface;

use SplFileInfo;
use StdClass;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Nyholm\Psr7\Factory\Psr17Factory; // optional
use Nyholm\Psr7Server\ServerRequestCreator; // optional

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
        RequestInterface|false $request = false
    ): self|false {
        if ($fileSystemRoot->notFound()) {
            return false;
        }
        return new self($fileSystemRoot, $request);
    }

    final private function __construct(
        private readonly Root $fileSystemRoot,
        private RequestInterface|false $request
    ) {
    }

    public function withRequest(RequestInterface $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function request(): RequestInterface
    {
        if ($this->request === false) {
            $psr17Factory = new Psr17Factory();

            $this->request = (new ServerRequestCreator(
                $psr17Factory, // ServerRequestFactory
                $psr17Factory, // UriFactory
                $psr17Factory, // UploadedFileFactory
                $psr17Factory  // StreamFactory
            ))->fromGlobals();
        }
        return $this->request;
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
}
