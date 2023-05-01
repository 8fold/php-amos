<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;
use StdClass;

class Site
{
    private string $contentRoot;

    private const COMPONENT_WRAPPER = '{!!(.*)!!}';

    public static function init(
        string $withDomain,
        string $contentIn
    ): self {
        return new self($withDomain, $contentIn);
    }

    final private function __construct(
        private readonly string $withDomain,
        private readonly string $contentIn
    ) {
    }

    public function domain(): string
    {
        return $this->withDomain;
    }

    public function contentRoot(): string
    {
        if (isset($this->contentRoot) === false) {
            $fileInfo = new SplFileInfo($this->contentIn);

            $p = $fileInfo->getRealPath();
            if ($p === false) {
                return '';
            }
            $this->contentRoot = $p;
        }
        return $this->contentRoot;
    }

    private function filePath(string $base, string $filename, string $at = ''): string
    {
        if (str_starts_with($at, '/') === false) {
            $at = '/' . $at;
        }

        if ($at === '/') {
            $at = '';
        }

        return $base . $at . '/' . $filename;
    }

    public function rootFilePath(string $filename, string $at = ''): string
    {
        return $this->filePath($this->contentRoot(), $filename, $at);
    }

    public function publicRoot(): string
    {
        return $this->contentRoot() . '/public';
    }

    public function publicFilePath(string $filename, string $at = ''): string
    {
        return $this->filePath($this->publicRoot(), $filename, $at);
    }

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
     * @param array<string, string> $components
     */
    private function processPartials(
        string $at,
        string $content,
        array $components
    ): string {
        $partials = [];
        if (
            preg_match_all(
                '/' . self::COMPONENT_WRAPPER . '/',
                $content,
                $partials // Populates $p
            )
        ) {
            $replacements = $partials[0];
            $templates    = $partials[1];

            for ($i = 0; $i < count($replacements); $i++) {
                $partial      = trim($templates[$i]);
                $partialParts = explode(':', $partial, 2);

                $partialKey  = $partialParts[0];
                $partialArgs = [];
                if (count($partialParts) > 1) {
                    $partialArgs = explode(',', $partialParts[1]);
                }

                if (! array_key_exists($partialKey, $components)) {
                    continue;
                }

                $template = $components[$partialKey];

                $content = str_replace(
                    $replacements[$i],
                    (string) $template::create($this, $at),
                    $content
                );
            }
        }
        return $content;
    }

    public function metaPath(string $at = ''): string
    {
        return $this->publicFilePath('meta.json', $at);
    }

    public function meta(string $at = ''): StdClass|false
    {
        $meta = $this->metaPath($at);
        if (is_file($meta) === false) {
            return false;
        }

        $json = file_get_contents($meta);
        if ($json === false) {
            return false;
        }

        $decoded = json_decode($json);
        if (
            is_object($decoded) === false or
            is_a($decoded, StdClass::class) === false
        ) {
            return false;
        }

        return $decoded;
    }

    public function title(string $at = ''): string
    {
        if (str_starts_with($at, '/') === false) {
            $at = '/' . $at;
        }

        $meta = $this->meta($at);
        if (
            $meta === false or
            property_exists($meta, 'title') === false
        ) {
            return '';
        }

        return $meta->title;
    }

    /**
     * @return string[]
     */
    public function titles(string $at = ''): array
    {
        if (str_starts_with($at, '/')) {
            $at = substr($at, 1);
        }

        $titles = [];
        $parts = array_filter(explode('/', $at));
        while (count($parts) > 0) {
            $path = implode('/', $parts);
            $titles[] = $this->title($path);

            array_pop($parts);
        }

        $titles[] = $this->title('/');

        return array_filter($titles);
    }

    public function hasPublishedContent(string $at = ''): bool
    {
        return is_file($this->metaPath($at));
    }
}
