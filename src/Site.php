<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;

use Eightfold\Amos\Content;
use Eightfold\Amos\Markdown;

use Eightfold\Amos\Documents\Page;

class Site
{
    private RequestInterface $request;

    private UriInterface $uri;

    public static function init(
        string $withDomain,
        Content $contentIn,
    ): self
    {
        return new Site($withDomain, $contentIn);
    }

    final private function __construct(
        private string $withDomain,
        private Content $contentIn
    ) {
    }

    public function domain(): string
    {
        return $this->withDomain;
    }

    public function content(): Content
    {
        return $this->contentIn;
    }

    public function response(RequestInterface $for): ResponseInterface
    {
        $this->request = $for;

        if ($this->content()->notFound(at: $this->requestPath())) {
            die('404');
        }

        return new Response(
            status: 200,
            headers: ['Content-type' => 'text/html'],
            body: Stream::create(
                Page::create(
                    $this->content()->publicContentRoot(),
                    $this->content()->publicPath(at: $this->requestPath()),
                    $this->content()->convertedContent(at: $this->requestPath())
                )->build()
            )
        );
    }

    private function requestPath(): string
    {
        $path = $this->request()->getUri()->getPath();
        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }
        return $path;
    }

    private function request(): RequestInterface
    {
        if (isset($this->request) === false) {
            trigger_error("No request received.", E_USER_WARNING);
        }
        return $this->request;
    }
}
