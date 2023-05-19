<?php
declare(strict_types=1);

namespace Eightfold\Amos\Php;

use voku\helper\ASCII;

class StrToSlug
{
    /**
     * @param array<string, string> $replacements
     */
    public static function fromString(
        string $string,
        string $separator = '-',
        string $language = 'en',
        array $replacements = ['@' => 'at']
    ): string {
        $instance = new self();
        return $instance($string, $separator, $language, $replacements);
    }

    /**
     * @param array<string, string> $replacements
     */
    public function __invoke(
        string $string,
        string $separator = '-',
        string $language = 'en',
        array $replacements = ['@' => 'at']
    ): string {
        $string = ASCII::to_ascii($string, $language); // @phpstan-ignore-line

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $string = preg_replace(
            '![' . preg_quote($flip) . ']+!u',
            $separator,
            $string
        );

        if ($string == null) {
            return '';
        }

        // Replace dictionary words
        foreach ($replacements as $key => $value) {
            $replacements[$key] = $separator . $value . $separator;
        }

        $string = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $string
        );

        // Remove all characters that are not the separator, letters, numbers, or whitespace
        $string = preg_replace(
            '![^' . preg_quote($separator) . '\pL\pN\s]+!u',
            '',
            mb_strtolower($string, 'UTF-8')
        );

        if ($string == null) {
            return '';
        }

        // Replace all separator characters and whitespace by a single separator
        $string = preg_replace(
            '![' . preg_quote($separator) . '\s]+!u',
            $separator,
            $string
        );

        if ($string == null) {
            return '';
        }

        return trim($string, $separator);
    }
}
