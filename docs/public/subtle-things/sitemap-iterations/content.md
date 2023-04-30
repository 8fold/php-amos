# Sitemap iterations

## Iteration 1

```php
<?php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../functions.php';

$url = 'https://8fold.pro/amos';

header('Content-type: application/xml');
?>
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<urlset>
<?php
foreach (scandir($contentPublicRoot) as $fileOrFolder) {
  if ($fileOrFolder === '.' or $fileOrFolder === '..') {
    continue;
  }

  if ($fileOrFolder === 'meta.json') {
    $meta = meta_for_file_path($contentPublicRoot . '/' . $fileOrFolder);

    // Could not create meta object.
    if ($meta === false) {
      continue;
    }

    $location = $url;
    if (str_ends_with($location, '/') === false) {
      $location = $url . '/';
    }

    print '<url>';
    print '<loc>' . $location . '</loc>';

    $lastmod = '';
    if (property_exists($meta, 'updated')) {
      $lastmod = $meta->updated;

    } elseif (property_exists($meta, 'created')) {
      $lastmod = $meta->created;

    }

    if (strlen($lastmod) > 0) {
      $date = date_create_from_format('Ymd', $lastmod);
      print '<lastmod>' . date_format($date, 'Y-m-d') . '</lastmod>';
    }

    print '</url>';
  }
}
?>
</urlset>
```

This got us to the point where the home page was in the sitemap:

```xml
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<urlset>
  <url>
    <loc>http://yourdomain.com/</loc>
    <lastmod>2023-03-27</lastmod>
  </url>
</urlset>
```

But oh my goodness, that code is hard to read, and we need to be able to do *all* the pages. That means going into any subdirectories and any subdirectories those might have.

## Iteration 2

Let's extract a meta method. It will return the string for the `url` tag entry.

```php
<?php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../functions.php';

$url = 'https://yourdomain.com';

header('Content-type: application/xml');

function meta_sitemap_url(string $url, \StdClass $meta): string
{
  $location = $url;
  if (str_ends_with($location, '/') === false) {
    $location = $url . '/';
  }

  $contents = '<loc>' . $location . '</loc>';

  $lastmod = '';
  if (property_exists($meta, 'updated')) {
    $lastmod = $meta->updated;

  } elseif (property_exists($meta, 'created')) {
    $lastmod = $meta->created;

  }

  if (strlen($lastmod) > 0) {
    $date    = date_create_from_format('Ymd', $lastmod);
    $contents .= '<lastmod>' . date_format($date, 'Y-m-d') . '</lastmod>';
  }

  return '<url>' . $contents . '</url>';
}
?>
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<urlset>
<?php
foreach (scandir($contentPublicRoot) as $fileOrFolder) {
  if ($fileOrFolder === '.' or $fileOrFolder === '..') {
    continue;
  }

  if ($fileOrFolder === 'meta.json') {
    $meta = meta_for_file_path($contentPublicRoot . '/' . $fileOrFolder);

    // Could not create meta object.
    if ($meta === false) {
      continue;
    }

    // Extract method
    print meta_sitemap_url($url, $meta);
  }
}
?>
</urlset>
```

It's a little easier to read, but we're still not getting to the about page.

## Iteration 3

```php
<?php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../functions.php';

$url = 'https://yourdomain.com';

header('Content-type: application/xml');

function meta_sitemap_url(string $url, \StdClass $meta): string
{
  $location = $url;
  if (str_ends_with($location, '/') === false) {
    $location = $url . '/';
  }

  $contents = '<loc>' . $location . '</loc>';

  $lastmod = '';
  if (property_exists($meta, 'updated')) {
    $lastmod = $meta->updated;

  } elseif (property_exists($meta, 'created')) {
    $lastmod = $meta->created;

  }

  if (strlen($lastmod) > 0) {
    $date    = date_create_from_format('Ymd', $lastmod);
    $contents .= '<lastmod>' . date_format($date, 'Y-m-d') . '</lastmod>';
  }

  return '<url>' . $contents . '</url>';
}

function sitemap_url_set(string $url, string $directoryPath): string
{
  $urlSet = '';
  foreach (scandir($directoryPath) as $fileOrFolder) {
    if ($fileOrFolder === '.' or $fileOrFolder === '..') {
      continue;
    }

    if ($fileOrFolder === 'meta.json') {
      $meta = meta_for_file_path($directoryPath . '/' . $fileOrFolder);

      // Could not create meta object.
      if ($meta === false) {
        continue;
      }

      // Extract method
      $urlSet .= meta_sitemap_url($url, $meta);
    }
  }
  return $urlSet;
}
?>
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<urlset>
<!-- Extract method -->
<?php print sitemap_url_set($url, $contentPublicRoot); ?>
</urlset>
```

## Iteration 4

```php
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
```

