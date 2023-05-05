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
}
