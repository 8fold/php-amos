<?php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../functions.php';

// Change variable name to "domain".
$domain = 'https://8fold.pro/amos';

// Ensure the browser knows to render this as XML.
header('Content-type: application/xml');

// We'll need the domain, the content public root folder path, and
// a directory path to iterate over.
function sitemap_url_set(
    string $domain,
    string $contentPublicRoot,
    string $directoryPath
): string {
    $urlSet = '';

    // Iterate over the directory path for files and directories.
    foreach (scandir($directoryPath) as $fileOrDirectory) {
        // Skip hidden.
        if (str_starts_with($fileOrDirectory, '.')) {
            continue;
        }

        // Create fully qualified path.
        $path = $directoryPath . '/' . $fileOrDirectory;

        // Recursion point: If the path is a directory, process that directory.
        if (is_dir($path)) {
            $urlSet .= sitemap_url_set($domain, $contentPublicRoot, $path);
            continue;
        }

        // If the file isn't  meta.json, skip it..
        if ($fileOrDirectory !== 'meta.json') {
            continue;
        }

        if ($meta = meta_for_file_path($path)) {
            // Extract function.
            $uriPath  = request_path_from_meta_file_path($contentPublicRoot, $path);

            // Prepare fully qualified URL.
            $location = $domain . $uriPath;

            // Add URL entry.
            $urlSet  .= sitemap_url_for_meta($location, $meta);

        }
    }

    return $urlSet;
}

// Convert a file path back to a request path, inverse of
// meta_file_path_from_requested_path in functions.php
function request_path_from_meta_file_path(string $root, string $metaPath): string
{
    return str_replace([$root, 'meta.json'], '', $metaPath);
}

// Rename.
function sitemap_url_for_meta(string $location, \StdClass $meta): string
{
    if (str_ends_with($location, '/') === false) {
        $location = $location . '/';
    }

    $contents = '<loc>' . $location . '</loc>';

    $lastmod = '';
    if (property_exists($meta, 'updated')) {
        $lastmod = $meta->updated;

    } elseif (property_exists($meta, 'created')) {
        $lastmod = $meta->created;

    }

    if (strlen($lastmod) > 0) {
        $date      = date_create_from_format('Ymd', $lastmod);
        $contents .= '<lastmod>' . date_format($date, 'Y-m-d') . '</lastmod>';
    }

    return '<url>' . $contents . '</url>';
}
?>
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<urlset>
<?php print sitemap_url_set($domain, $contentPublicRoot, $contentPublicRoot); ?>
</urlset>
