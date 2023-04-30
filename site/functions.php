<?php
function page_title(
    string $contentPublicRoot,
    string $requestedPath
): string {
    // Extract function.
    if (did_not_find_content_public_root($contentPublicRoot)) {
        return '';
    }

    // Extract function.
    $requestedPath = directory_path_from_requested_path($requestedPath);

    $pathParts = explode('/', $requestedPath);

    $titles = [];

    $pageNotFound = false;
    while (count($pathParts) > 0) {
        $pathToCheck = implode('/', $pathParts);

        // Extract function.
        $metaPath = meta_file_path_from_requested_path($contentPublicRoot, $pathToCheck);

        // Extract function.
        $meta = meta_for_file_path($metaPath);
        if ($meta === false) {
            $pageNotFound = true;

            $titles[] = 'Page not found';

            array_pop($pathParts);
            continue;
        }

        if ($pageNotFound and count($pathParts) > 1) {
            array_pop($pathParts);
            continue;
        }

        if (property_exists($meta, 'title') === false) {
            array_pop($pathParts);
            continue;
        }

        $titles[] = $meta->title;

        array_pop($pathParts);
    }

    return implode(' | ', $titles);
}

function did_not_find_content_public_root(string $contentPublicRoot): bool
{
    return ! found_content_public_root($contentPublicRoot);
}

function found_content_public_root(string $contentPublicRoot): bool
{
    return is_dir($contentPublicRoot);
}

function directory_path_from_requested_path(string $requestedPath): string
{
    if (str_ends_with($requestedPath, '/')) {
        $requestedPath = substr($requestedPath, 0, -1);
    }
    return $requestedPath;
}

function meta_for_file_path(string $metaPath): \StdClass|false
{
    if (is_file($metaPath) === false) {
        return false;
    }

    $json = file_get_contents($metaPath);
    if (
        $json === false or
        strlen($json) === 0
    ) {
        return false;
    }

    $meta = json_decode($json);
    if ($meta === false) {
        return false;
    }

    return $meta;
}

function meta_file_path_from_requested_path(
    string $root,
    string $requestedPath
): string
{
    return $root . directory_path_from_requested_path($requestedPath) . '/meta.json';
}
?>
