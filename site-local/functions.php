<?php
function page_title(
    string $contentPublicRoot,
    string $requestedPath
): string {
    // Does the content public root directory exist.
    if (is_dir($contentPublicRoot) === false) {
        return '';
    }

    // If the requested path ends in a forward slash, remove it.
    if (str_ends_with($requestedPath, '/')) {
        $requestedPath = substr($requestedPath, 0, -1);
    }

    // Convert the requested path into an array of parts based on forward slashes.
    $pathParts = explode('/', $requestedPath);

    // Prepare a container for the titles we find.
    $titles = [];

    // As long as there is a path part to examine, do so.
    while (count($pathParts) > 0) {
        // Convert the array of parts back to a string.
        $pathToCheck = implode('/', $pathParts);

        $metaPath = $contentPublicRoot . $pathToCheck . '/meta.json';
        if (is_file($metaPath) === false) {
            // Remove the last path part.
            array_pop($pathParts);

            // Skip to the next loop; wish this was called skip instead of continue.
            continue;
        }

        $json = file_get_contents($metaPath);
        if ($json === false) {
            array_pop($pathParts);
            continue;
        }

        $meta = json_decode($json);
        if ($meta === false) {
            array_pop($pathParts);
            continue;
        }

        if (property_exists($meta, 'title') === false) {
            array_pop($pathParts);
            continue;
        }

        // Add the title to the collection of titles.
        $titles[] = $meta->title;

        // Remove the last path part.
        array_pop($pathParts);
    }

    // Combine all the titles together with a pipe separating them.
    return implode(' | ', $titles);
}
?>
