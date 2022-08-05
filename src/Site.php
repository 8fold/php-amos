<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use StdClass;
use SplFileInfo;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Psr\Http\Server\RequestHandlerInterface;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;

use Eightfold\Markdown\Markdown as MarkdownConverter;

use Eightfold\Amos\Templates\Page;
use Eightfold\Amos\Templates\PageNotFound;

use Eightfold\Amos\Documents\Sitemap;

class Site implements RequestHandlerInterface
{
    /**
     * Initialization
     */

    public static function init(
        string $withDomain,
        string $contentIn,
    ): self {
        self::$singleton = new Site($withDomain, $contentIn);
        return self::singleton();
    }

    final private function __construct(
        private string $withDomain,
        private string $contentIn
    ) {
    }

    public function domain(): string
    {
        return $this->withDomain;
    }

    /**
     * Singleton.
     */
    private static self $singleton;

    public static function singleton(): self
    {
        return self::$singleton;
    }

    /**
     * Request
     */
    private RequestInterface $request;

    public function request(): RequestInterface
    {
        if (isset($this->request) === false) {
            trigger_error("No request received.", E_USER_WARNING);
        }
        return $this->request;
    }

    public function requestPath(): string
    {
        $path = $this->request()->getUri()->getPath();
        return rtrim($path, '/');
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        if ($this->requestPath() === '/sitemap.xml') {
            return new Response(
                status: 200,
                headers: ['Content-type' => 'application/xml'],
                body: Stream::create(
                    Sitemap::create($this)->build()
                )
            );

        } elseif (str_contains($this->requestPath(), '.')) {
            $path = $this->publicRoot() . $this->requestPath();
            if (file_exists($path)) {
                $mime = mime_content_type($path);

                $resource = @\fopen($path, 'r');
                if (is_resource($resource)) {
                    return new Response(
                        status: 200,
                        headers: ['Content-type' => mime_content_type($path)],
                        body: Stream::create($resource)
                    );
                }
            }
        }

        if (method_exists($this::class, 'createMarkdownConverter')) {
            $this->createMarkdownConverter();
        }

        if ($this->isPublishedContent($this->requestPath()) === false) {
            $path = $this->contentRoot() . '/errors/404/content.md';
            if (file_exists($path)) {
                $template = $this->templates['error404'];
                return new Response(
                    status: 404,
                    headers: ['Content-type' => 'text/html'],
                    body: Stream::create(
                        $template::create($this)->build()
                    )
                );

            } else {
                // No custom 404 page error content found.
                return new Response(
                    status: 404,
                    headers: ['Content-type' => 'text/html'],
                    body: Stream::create('404: Page not found.')
                );
            }
        }

        $template = $this->templates['default'];
        return new Response(
            status: 200,
            headers: ['Content-type' => 'text/html'],
            body: Stream::create(
                $template::create($this)->build()
            )
        );
    }

    /**
     * File system
     */
    private string $realRootPath = '';

    public function contentRoot(): string
    {
        if ($this->realRootPath === '') {
            $fileInfo = new SplFileInfo($this->contentIn);
            $this->realRootPath = $fileInfo->getRealPath();
        }
        return $this->realRootPath;
    }

    public function publicRoot(): string
    {
        return $this->contentRoot() . '/public';
    }

    public function meta(string $at): StdClass|false
    {
        $path = $this->metaPath($at);

        if (is_file($path) === false) {
            return false;
        }

        $json = file_get_contents($path);
        if ($json === false) {
            return false;
        }

        $decoded = json_decode($json);
        if (
            is_object($decoded) and
            is_a($decoded, StdClass::class)
        ) {
            return $decoded;
        }
        return false;
    }

    public function textFile(string $named, string $at): string|false
    {
        $path = $this->contentRoot() . $at . '/' . $named;
        if (is_file($path) === false) {
            return false;
        }

        return file_get_contents($path);
    }

    private function metaPath(string $at): string
    {
        return $this->publicRoot() . $at . '/meta.json';
    }

    public function content(string $at): string
    {
        $path = $this->contentPath($at);

        if (file_exists($path) === false) {
            return '';
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return '';
        }
        return $content;
    }

    public function isPublishedContent(string $at): bool
    {
        return file_exists($this->contentPath($at)) and
            file_exists($this->metaPath($at));
    }

    public function contentPath(string $at): string
    {
        return $this->publicRoot() . $at . '/content.md';
    }

    public function decodedJsonFile(string $named, string $at): StdClass|false
    {
        $path = $this->publicRoot() . $at . $named;
        if (is_file($path) === false) {
            return false;
        }

        $json = $this->content($at);
        if ($json === false) {
            return false;
        }

        $decoded = json_decode($json);
        if (
            is_object($decoded) and
            is_a($decoded, StdClass::class)
        ) {
            return $decoded;
        }

        return false;
    }

    /**
     * User- specified templates.
     *
     * @var array<string, string>
     */
    private array $templates = [
        'default'  => Page::class,
        'error404' => PageNotFound::class
    ];

    /**
     *
     * @param string $default
     * @param array<string, string> $templates
     *
     * @return self
     */
    public function setTemplates(
        string $default,
        array $templates = []
    ): self {
        $this->templates['default'] = $default;
        foreach ($templates as $id => $className) {
            $this->templates[$id] = $className;
        }
        return $this;
    }

    /**
     *
     * @return array<string, string>
     */
    public function templates(): array
    {
        return $this->templates;
    }

    public function template(string $at): string
    {
        $templates = $this->templates();
        return $templates[$at];
    }

    /**
     * @deprecated 2.0.0 Switched to using RequestHandlerInterface method signature.
     *
     * @param RequestInterface $for
     *
     * @return ResponseInterface
     */
    public function response(RequestInterface $for): ResponseInterface
    {
        return $this->handle($for);
    }
}
