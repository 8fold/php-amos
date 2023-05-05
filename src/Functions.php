<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use StdClass;

use Symfony\Component\Finder\Finder;

use Eightfold\Amos\Logger\Logger;
use Eightfold\Amos\Logger\Log;

function real_path_for_dir(string $base, string $at = ''): string
{
    if (str_starts_with($at, '/') === false) {
        $at = '/' . $at;
    }

    if ($at === '/') {
        $at = '';
    }

    $propsedDir = $base . $at;
    if (is_dir($propsedDir) === false) {
        return '';
    }

    $real_path = realpath($propsedDir);
    if ($real_path === false) {
        return '';
    }
    return $real_path;
}

function real_path_for_public_dir(string $base, string $at = ''): string
{
    return real_path_for_dir($base . '/public', $at);
}

function real_path_for_file(
    string $base,
    string $filename,
    string $at = ''
): string {
    $propsedDir   = real_path_for_dir($base, $at);
    $proposedFile = $propsedDir . '/' . $filename;
    if (is_file($proposedFile) === false) {
        return '';
    }

    $real_path = realpath($proposedFile);
    if ($real_path === false) {
        return '';
    }
    return $real_path;
}

function real_path_for_public_file(
    string $base,
    string $filename,
    string $at = ''
): string {
    return real_path_for_file(
        real_path_for_public_dir($base),
        $filename,
        $at
    );
}

function real_path_for_public_meta(string $base, string $at = ''): string
{
    return real_path_for_public_file($base, 'meta.json', $at);
}

/**
 * @return string[]
 */
function real_paths_for_files_named(string $base, string $filename): array
{
    $iterator = (new Finder())->files()->name($filename)->in($base);

    $array = iterator_to_array($iterator);

    return array_keys($array);
}

/**
 * @return string[]
 */
function real_paths_for_public_meta_files(string $base): array
{
    return real_paths_for_files_named(
        real_path_for_public_dir($base),
        'meta.json'
    );
}

function content_for_file(
    string $base,
    string $filename,
    string $at = '',
    Logger|false $logger = false
): string {
    $real_path = real_path_for_file($base, $filename, $at);
    if (file_exists($real_path) === false) {
        if ($logger !== false and is_a($logger, Logger::class)) {
            $logger->error(
                Log::with(
                    'JSON file not found: {path}.',
                    context: ['path' => $real_path]
                )
            );
        }
        return '';
    }

    $content = file_get_contents($real_path);
    if ($content === false) {
        if ($logger !== false and is_a($logger, Logger::class)) {
            $logger->error(
                Log::with(
                    'Failed to get file contents: {path}.',
                    context: ['path' => $real_path]
                )
            );
        }
        return '';
    }

    return $content;
}

function object_from_json_in_file(
    string $base,
    string $filename,
    string $at = '',
    Logger|false $logger = false
): StdClass {
    $json = content_for_file($base, $filename, $at, $logger);
    $obj  = json_decode($json);
    if (is_object($obj) === false or is_a($obj, StdClass::class) === false) {
        if ($logger !== false and is_a($logger, Logger::class)) {
            $logger->error(
                Log::with(
                    'Failed to decode JSON: {path}.',
                    context: ['path' => real_path_for_file($base, $filename, $at)]
                )
            );
        }
        return new StdClass();
    }

    return $obj;
}

function object_from_json_in_public_file(
    string $base,
    string $filename,
    string $at = '',
    Logger|false $logger = false
): StdClass {
    return object_from_json_in_file(
        real_path_for_public_dir($base, $at),
        $filename,
        logger: $logger
    );
}

function meta_file_exists_in_public_dir(
    string $base,
    string $at = ''
): bool {
    return is_file(
        real_path_for_public_meta($base, $at)
    );
}

function meta_object_in_public_dir(
    string $base,
    string $at = '',
    Logger|false $logger = false
): StdClass {
    return object_from_json_in_public_file($base, 'meta.json', $at, $logger);
}

function title_for_meta_object_in_public_dir(
    string $base,
    string $at = '',
    Logger|false $logger = false
): string {
    if (str_starts_with($at, '/') === false) {
        $at = '/' . $at;
    }

    $meta = meta_object_in_public_dir($base, $at);
    if (property_exists($meta, 'title') === false) {
        return '';
    }
    return $meta->title;
}

/**
 * @return string[]
 */
function titles_for_meta_objects_in_public_dir(
    string $base,
    string $at = '',
    Logger|false $logger = false
): array {
    if (str_starts_with($at, '/')) {
        $at = substr($at, 1);
    }

    $titles = [];
    $parts = array_filter(explode('/', $at));
    while (count($parts) > 0) {
        $path = implode('/', $parts);
        $titles[] = title_for_meta_object_in_public_dir($base, $at);

        array_pop($parts);
    }

    $titles[] = title_for_meta_object_in_public_dir($base, '/');

    return array_filter($titles);
}
