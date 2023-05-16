<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use StdClass;

use Symfony\Component\Finder\Finder;

use Eightfold\Amos\Logger\Logger;
use Eightfold\Amos\Logger\Log;

// function public_directory_name(): string
// {
//     return '/public';
// }

// function meta_filename(): string
// {
//     return 'meta.json';
// }

// function content_filename(): string
// {
//     return 'content.md';
// }

// function real_path_for_dir(string $contentRoot, string $at = ''): string
// {
//     if (str_starts_with($at, '/') === false) {
//         $at = '/' . $at;
//     }
//
//     if ($at === '/') {
//         $at = '';
//     }
//
//     $real_path = realpath($contentRoot . $at);
//     if ($real_path === false) {
//         return '';
//     }
//     return $real_path;
// }

// function real_path_for_public_dir(string $contentRoot, string $at = ''): string
// {
//     return real_path_for_dir(
//         $contentRoot . public_directory_name(),
//         $at
//     );
// }

// function real_path_for_file(
//     string $contentRoot,
//     string $filename,
//     string $at = ''
// ): string {
//     $propsedDir   = real_path_for_dir($contentRoot, $at);
//     $proposedFile = $propsedDir . '/' . $filename;
//
//     $real_path = realpath($proposedFile);
//     if ($real_path === false) {
//         return '';
//     }
//     return $real_path;
// }

// function real_path_for_meta_file(
//     string $contentRoot,
//     string $at = ''
// ): string {
//     return real_path_for_file(
//         real_path_for_dir($contentRoot, $at),
//         meta_filename()
//     );
// }

// function real_path_for_public_file(
//     string $contentRoot,
//     string $filename,
//     string $at = ''
// ): string {
//     return real_path_for_file(
//         real_path_for_public_dir($contentRoot),
//         $filename,
//         $at
//     );
// }

// function real_path_for_public_meta_file(string $contentRoot, string $at = ''): string
// {
//     return real_path_for_public_file($contentRoot, meta_filename(), $at);
// }

/**
 * @return string[]
 */
// function real_paths_for_files_named(string $contentRoot, string $filename): array
// {
//     $iterator = (new Finder())->files()->name($filename)->in($contentRoot);
//
//     $array = iterator_to_array($iterator);
//
//     return array_keys($array);
// }

/**
 * @return string[]
 */
// function real_paths_for_public_files_named(
//     string $contentRoot,
//     string $filename
// ): array {
//     return real_paths_for_files_named(
//         real_path_for_public_dir($contentRoot),
//         $filename
//     );
// }

/**
 * @return string[]
 */
// function real_paths_for_public_meta_files(string $contentRoot): array
// {
//     return real_paths_for_public_files_named($contentRoot, meta_filename());
// }

// function contents_of_file(
//     string $contentRoot,
//     string $filename,
//     string $at = '',
//     Logger|false $logger = false
// ): string {
//     $real_path = real_path_for_file($contentRoot, $filename, $at);
//
//     if (strlen($real_path) === 0) {
//         if ($logger !== false and is_a($logger, Logger::class)) {
//             $logger->error(
//                 Log::with(
//                     'File not found: {path}.',
//                     context: ['path' => $contentRoot . '/' . $at . '/' . $filename]
//                 )
//             );
//         }
//         return '';
//     }
//
//     $content = file_get_contents($real_path);
//     if ($content === false) {
//         if ($logger !== false and is_a($logger, Logger::class)) {
//             $logger->error(
//                 Log::with(
//                     'Failed to get file contents: {path}.',
//                     context: ['path' => $real_path]
//                 )
//             );
//         }
//         return '';
//     }
//
//     return $content;
// }

// function contents_of_public_file(
//     string $contentRoot,
//     string $filename,
//     string $at = '',
//     Logger|false $logger = false
// ): string {
//     return contents_of_file(
//         real_path_for_public_dir($contentRoot),
//         $filename,
//         $at,
//         $logger
//     );
// }

// function object_from_json_in_file(
//     string $contentRoot,
//     string $filename,
//     string $at = '',
//     Logger|false $logger = false
// ): StdClass {
//     $json = contents_of_file($contentRoot, $filename, $at, $logger);
//     $obj  = json_decode($json);
//     if (is_object($obj) === false or is_a($obj, StdClass::class) === false) {
//         if ($logger !== false and is_a($logger, Logger::class)) {
//             $logger->error(
//                 Log::with(
//                     'Failed to decode JSON: {path}.',
//                     context: ['path' => real_path_for_file($contentRoot, $filename, $at)]
//                 )
//             );
//         }
//         return new StdClass();
//     }
//     return $obj;
// }

// function object_from_json_in_public_file(
//     string $contentRoot,
//     string $filename,
//     string $at = '',
//     Logger|false $logger = false
// ): StdClass {
//     return object_from_json_in_file(
//         real_path_for_public_dir($contentRoot),
//         $filename,
//         $at,
//         $logger
//     );
// }

function meta_exists_in_dir(
    string $contentRoot,
    string $at = ''
): bool {
    $real_path = real_path_for_meta_file($contentRoot, $at);
    return strlen($real_path) > 0 and is_file($real_path);
}

function meta_exists_in_public_dir(
    string $contentRoot,
    string $at = ''
): bool {
    return meta_exists_in_dir(
        real_path_for_public_dir($contentRoot),
        $at
    );
}

// function meta_in_dir(
//     string $contentRoot,
//     string $at = '',
//     Logger|false $logger = false
// ): StdClass {
//     return object_from_json_in_file(
//         $contentRoot,
//         meta_filename(),
//         $at,
//         $logger
//     );
// }

// function meta_in_public_dir(
//     string $contentRoot,
//     string $at = '',
//     Logger|false $logger = false
// ): StdClass {
//     return meta_in_dir(
//         real_path_for_public_dir($contentRoot),
//         $at,
//         $logger
//     );
// }

function title_for_meta_in_dir(
    string $contentRoot,
    string $at = '',
    Logger|false $logger = false
): string {
    $meta = meta_in_dir($contentRoot, $at, $logger);
    if (property_exists($meta, 'title') === false) {
        return '';
    }
    return $meta->title;
}

function title_for_meta_in_public_dir(
    string $contentRoot,
    string $at = '',
    Logger|false $logger = false
): string {
    return title_for_meta_in_dir(
        real_path_for_public_dir($contentRoot),
        $at,
        $logger
    );
}







/**
 * @return string[]
 */
function titles_for_meta_in_public_dir(
    string $contentRoot,
    string $at = '',
    Logger|false $logger = false
): array {
    $titles = [];
    $parts = array_filter(explode('/', $at));
    while (count($parts) > 0) {
        $path = implode('/', $parts);
        $titles[] = title_for_meta_in_public_dir($contentRoot, $at);

        array_pop($parts);
    }

    $titles[] = title_for_meta_in_public_dir($contentRoot, '/');

    return array_filter($titles);
}
