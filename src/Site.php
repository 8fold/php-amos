<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use StdClass;
use SplFileInfo;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;

use Eightfold\Markdown\Markdown as MarkdownConverter;

use Eightfold\Amos\Documents\Page;
use Eightfold\Amos\Documents\Sitemap;

class Site
{
    /**
     * Initializer
     */
    public static function init(
        string $withDomain,
        string $contentIn,
    ): self {
        self::$singleton = new Site($withDomain, $contentIn);
        return self::singleton();
    }

    /**
     * Singleton
     */
    private static self $singleton;

    public static function singleton(): self
    {
        return self::$singleton;
    }

    /**
     * Instance
     */
    private string $realRootPath = '';

    private RequestInterface $request;

    /**
     *
     * @var array<string, string>
     */
    private array $templates = [
        'default' => Page::class
    ];

    final private function __construct(
        private string $withDomain,
        private string $contentIn
    ) {
    }

    public function requestPath(): string
    {
        $path = $this->request()->getUri()->getPath();
        return rtrim($path, '/');
    }

    public function domain(): string
    {
        return $this->withDomain;
    }

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

    public function decodedJsonFile(string $named, string $at): StdClass|false
    {
        $path = $this->publicRoot() . $at . $named;
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

    public function isPublishedContent(string $at): bool
    {
        return file_exists($this->contentPath($at)) and
            file_exists($this->metaPath($at));
    }

    public function contentPath(string $at): string
    {
        return $this->publicRoot() . $at . '/content.md';
    }

    private function metaPath(string $at): string
    {
        return $this->publicRoot() . $at . '/meta.json';
    }

    public function request(): RequestInterface
    {
        if (isset($this->request) === false) {
            trigger_error("No request received.", E_USER_WARNING);
        }
        return $this->request;
    }

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

    public function response(RequestInterface $for): ResponseInterface
    {
        $this->request = $for;

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

        $this->createMarkdownConverter();
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

    private function createMarkdownConverter(): void
    {
        Markdown::singletonConverter(
            MarkdownConverter::create()
                ->withConfig([
                    'html_input' => 'allow'
                ])->defaultAttributes([
                    Image::class => [
                        'loading'  => 'lazy',
                        'decoding' => 'async'
                    ]
                ])->externalLinks([
                    'open_in_new_window' => true,
                    'internal_hosts'     => $this->domain()
                ])->accessibleHeadingPermalinks([
                    'min_heading_level' => 2,
                    'max_heading_level' => 3,
                    'symbol'            => '＃'
                ])->minified()
                ->smartPunctuation()
                ->descriptionLists()
                ->tables()
                ->attributes() // for class on notices
                ->abbreviations()
        );
    }
}
