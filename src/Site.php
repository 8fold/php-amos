<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use SplFileInfo;
use StdClass;

use function Eightfold\Amos\real_path_for_public_meta;

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
            $this->contentRoot = real_path_for_dir($this->contentIn);
        }
        return $this->contentRoot;
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

    /**
     * @deprecated
     */
    public function publicRoot(): string
    {
        return real_path_for_public_dir($this->contentRoot());
    }

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
        $obj = meta_object_in_public_dir($this->contentRoot(), $at);
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
        return title_for_meta_object_in_public_dir($this->contentRoot(), $at);
    }

    /**
     * @deprecated
     * @return string[]
     */
    public function titles(string $at = ''): array
    {
        return titles_for_meta_objects_in_public_dir(
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
