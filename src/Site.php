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

    /**
     * @param array<string, string> $components
     */
    // private function processPartials(
    //     string $at,
    //     string $content,
    //     array $components
    // ): string {
        // partialsInPublicFileFromSite(
        //   $contentRoot,
        //   $wrapper,
        //   $components,
		//   $at = '',
		//   $logger = false
        // )

        // partialsInContent(
        //   $content,
        //   $wrapper,
        //   $components
        // )
//         $partials = [];
//         if (
//             preg_match_all(
//                 '/' . self::COMPONENT_WRAPPER . '/',
//                 $content,
//                 $partials // Populates $p
//             )
//         ) {
//             $replacements = $partials[0];
//             $templates    = $partials[1];
//
//             for ($i = 0; $i < count($replacements); $i++) {
//                 $replace = $replacements[$i];
//
//                 $partial = trim($templates[$i]);
//                 $args = [];
//                 if (str_contains($partial, ':')) {
//                     list($partial, $args) = explode(':', $partial, 2);
//                     $args = explode(',', $args);
//
//                 }


                // $partialKey  = $partialParts[0];
                // $partialArgs = [];
                // if (count($partialParts) > 1) {
                //     $partialArgs = explode(',', $partialParts[1]);
                // }

//                 if (! array_key_exists($partial, $components)) {
//                     continue;
//                 }
//
//                 $template = $components[$partialKey];
//
//                 $content = str_replace(
//                     $replacements[$i],
//                     (string) $template::create($this, $at),
//                     $content
//                 );
//             }
//         }
//         return $content;
//     }

    /**
     * @deprecated
     */
    // public function publicRoot(): string
    // {
    //     return real_path_for_public_dir($this->contentRoot());
    // }

    /**
     * @deprecated
     */
    public function rootFilePath(string $filename, string $at = ''): string
    {
        return real_path_for_file($this->contentRoot(), $filename, $at);
    }

    /**
     * @deprecated
     */
    public function publicFilePath(string $filename, string $at = ''): string
    {
        return real_path_for_public_file($this->contentRoot(), $filename, $at);
    }

    /**
     * @deprecated
     */
    public function publicMarkdown(string $filename, string $at = ''): string
    {
        $filePath = $this->publicFilePath($filename, $at);
        if (is_file($filePath) === false) {
            return '';
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            return '';
        }

        return $content;
    }

    /**
     * @deprecated
     *
     * @param array<string, string> $partials
     */
    public function content(string $at = '', array $partials = []): string
    {
        $content = $this->publicMarkdown('content.md', $at);
        if (strlen($content) === 0) {
            return '';
        }

        if (count($partials) > 0) {
            $content = $this->processPartials($at, $content, $partials);
        }

        return $content;
    }

    /**
     * @deprecated
     */
    public function metaPath(string $at = ''): string
    {
        return $this->publicFilePath('meta.json', $at);
    }

    /**
     * @deprecated
     */
    public function meta(string $at = ''): StdClass|false
    {
        $obj = meta_in_public_dir($this->contentRoot(), $at);
        if (count(get_object_vars($obj)) === 0) {
            return false;
        }
        return $obj;
    }

    /**
     * @deprecated
     */
    public function title(string $at = ''): string
    {
        return title_for_meta_in_public_dir($this->contentRoot(), $at);
    }

    /**
     * @deprecated
     * @return string[]
     */
    public function titles(string $at = ''): array
    {
        return titles_for_meta_in_public_dir(
            $this->contentRoot(),
            $at
        );
    }

    /**
     * @deprecated
     */
    public function hasPublishedContent(string $at = ''): bool
    {
        return is_file($this->metaPath($at));
    }
}
