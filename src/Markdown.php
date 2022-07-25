<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Markdown\Markdown as MarkdownConverter;

class Markdown
{
    private static MarkdownConverter $markdownConverter;

    private static MarkdownConverter $titleConverter;

    private const COMPONENT_WRAPPER = '{!!(.*)!!}';

    public static function singletonConverter(): MarkdownConverter
    {
        if (! isset(self::$markdownConverter)) {
            self::$markdownConverter = MarkdownConverter::create()
                ->withConfig(
                    [
                        'html_input' => 'allow'
                    ]
                )->minified()
                ->smartPunctuation()
                ->descriptionLists()
                ->attributes() // for class on notices
                ->defaultAttributes([
                    Image::class => [
                        'loading'  => 'lazy',
                        'decoding' => 'async'
                    ]
                ])->abbreviations()
                ->externalLinks(
                    [
                        'open_in_new_window' => true,
                        'internal_hosts'     => 'joshbruce.com'
                    ]
                );
        }
        return self::$markdownConverter;
    }

    public static function singletonTitleConverter(): MarkdownConverter
    {
        if (! isset(self::$titleConverter)) {
            self::$titleConverter = MarkdownConverter::create()
                ->smartPunctuation();
        }
        return self::$titleConverter;
    }

    public static function convert(
        string $markdown,
        array $components = []
    ): string {
        if (count($components) > 0) {
            $markdown = self::proccessPartials($markdown, $components);
        }
        return self::singletonConverter()->convert($markdown);
    }

    public static function convertTitle(string $title): string
    {
        return strip_tags(self::singletonTitleConverter()->convert($title));
    }

    private static function proccessPartials(
        string $markdown,
        array $components
    ): string {
        $partials = [];
        if (
            preg_match_all(
                '/' . self::COMPONENT_WRAPPER . '/',
                $markdown,
                $partials // Populates $partials
            )
        ) {
            $replacements = $partials[0];
            $templates    = $partials[1];

            for ($i = 0; $i < count($replacements); $i++) {
                $partial      = trim($templates[$i]);
                $partialParts = explode(':', $partial, 2);

                $partialKey  = $partialParts[0];
                $partialargs = [];
                if (count($partialParts) > 1) {
                    $partialargs = explode(',', $partialParts[1]);
                }

                if (! array_key_exists($partialKey, $components)) {
                    continue;
                }

                $template = $components[$partialKey];

                $markdown = str_replace(
                    $replacements[$i],
                    $template::create(...$partialargs)->build(),
                    $markdown
                );
            }
        }
        return $markdown;
    }
}
