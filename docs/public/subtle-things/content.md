# Subtle things

When I see folks beginning to develop websites, these subtle things are often missed and can have a great impact.

## Custom error pages

Part of this will involve concepts explained more deeply in the [.htaccess article](../htaccess/).

The main rule is that a custom error page should not redirect a user to a different URL. If the user types `/about/` and that page doesn't exist, they shouldn't be redirected to `/404/` or something similar. You might ask, "Why not?" And it's because that's not the expected behavior.

There are three custom error pages I recommend:

1. Page not found (404),
2. Internal server error (500), and
3. Method not allowed (405).

### Page not found (404)

We'll presume you have a server running and do not yet have a `.htaccess` file. Go to a URL that doesn't exist. You should see a "Not found" page. If you go to your browser's address bar, you should see the URL you entered; you didn't get redirected.

Some hosts will present a branded 404 page. Some frameworks may do the same, or the design of their 404 page is iconic enough that people will likely guess what framework you're using.

This exposes a security vulnerability. By putting in a page that doesn't exist, I may be able to identify what hosting service you use, what framework you're using to build your site, or both.

From a user experience perspective, having a custom error page serves multiple purposes:

1. I can feel somewhat confident that I'm at least in the right ballpark because I know the brand and recognize where I am based on the page's design. Go to [Apple's website](https://www.apple.com/) or [GitHub's website](https://github.com/) and add something to the end of the URL in the address bar that most likely doesn't exist. The page you end up on instills some confidence you're at least looking at Apple or GitHub.
2. It gives us an opportunity to help the user get to where they were trying to go by providing our website's navigation and may a search bar. The user is less likely to give up. Whereas with a relatively blank page and no navigation, they'll probably bail.

All right, how do we do it?

The most common custom error page we'll want is when a URL can't resolve to a known page on our site. Even if you're new to web development, you probably know what a 404 error is. It's one of about 500 [HTTP status codes](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status), and means the requested page or file could not be found by the server.

Add a `.htacess` file to our project inside the `/public` directory (if have the ability to editor our server's configuration and know how to, you should do that instead):

```bash
/project
├─ /docs
│   └─ /public
│       ├─ meta.json
│       └─ /about
│           └─ meta.json
└─ /site
    ├─ functions.php
    ├─ /partials
    │   ├─ opening.php
    │   └─ closing.php
    └─ /public
        ├─ .htaccess
        ├─ index.php
        └─ /about
            └─ index.php
```

The leading dot on the filename indicates a "hidden" file; the same is true for folders. Inside the `.htaccess`, put the following:

```bash
# Handle errors
ErrorDocument 404 /error-404.php
```

The first line starting with the hash (#), is a comment the server will ignore. The `ErrorDocument` part tells the server what we want to configure. The `404` part tells the server which error we want to use our document for. The `/error-404.php` tells the server where the file is relative to this `.htaccess` file.

Let's add the `error-404.php` file to our project:

```bash
/project
├─ /docs
│   └─ /public
│       ├─ meta.json
│       └─ /about
│           └─ meta.json
└─ /site
    ├─ functions.php
    ├─ /partials
    │   ├─ opening.php
    │   └─ closing.php
    └─ /public
        ├─ .htaccess
        ├─ index.php
        ├─ error-404.php
        └─ /about
            └─ index.php
```

404 errors are what I would consider soft failures. Chances are everything worked as expected. It's just invalid, like someone calling the wrong number. The phone number exists. The phone made the call. The other phone received the call. Someone even answered the phone. It just wasn't who we expected.

That means we can treat this page like any other page on our site, partials and all.

Let's add the following to the `error-404.php` file:

```php
<?php require_once __DIR__ . '/../partials/opening.php'; ?>

<h1>Page not found!</h1>
<p>Oh no!</p>

<?php require_once __DIR__ . '/../partials/closing.php'; ?>
```

We're going to want a proper page title, yeah?

That's a problem.

1. We don't know the path we will receive.
2. Our title-building script doesn't know how to change or set a title for a page that doesn't exist.

We want the title to be "Page not found | Your home page title".

We could just not use the `opening` script and have the [.Hypertext Markup Language](HTML); however, as the `opening` evolves, those changes might impact the 404 page, and we'll need to remember to update the `error-404.php` script.

Let's see if we can modify our title-building function.

```php
<?php
function page_title(
    string $contentPublicRoot,
    string $requestedPath
): string {
    if (is_dir($contentPublicRoot) === false) {
        return '';
    }

    if (str_ends_with($requestedPath, '/')) {
        $requestedPath = substr($requestedPath, 0, -1);
    }

    $pathParts = explode('/', $requestedPath);

    $titles = [];

    // Store state regarding whether we hit a page not found problem.
    $pageNotFound = false;
    while (count($pathParts) > 0) {
        $pathToCheck = implode('/', $pathParts);

        $metaPath = $contentPublicRoot . $pathToCheck . '/meta.json';
        // If the page isn't found and we haven't already encountered a missing page.
        if (is_file($metaPath) === false and $pageNotFound === false) {
            // Update state that a page wasn't found.
            $pageNotFound = true;

            // Add the page not found title.
            $titles[] = 'Page not found';

            array_pop($pathParts);
            continue;
        }

        // If we've run into a page not found problem, and there are more
        // parts of the URL to go through, skip them. This ensures the root
        // title will always be there.
        if ($pageNotFound and count($pathParts) > 1) {
            array_pop($pathParts);
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

        $titles[] = $meta->title;

        array_pop($pathParts);
    }

    return implode(' | ', $titles);
}
?>
```

If we visit a nonexistent page now, we should see the expected `title`.

This function is starting to feel a bit difficult to read. Let's refactor.

```php
// functions.php
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
  $requestedPath = convert_requested_uri_to_file_path($requestedPath);

  $pathParts = explode('/', $requestedPath);

  $titles = [];

  $pageNotFound = false;
  while (count($pathParts) > 0) {
    $pathToCheck = implode('/', $pathParts);

    // Extract function.
    $metaPath = meta_file_path_from_requested_path($contentPublicRoot, $pathToCheck);

    // Extract function.
    $meta = meta_for_path($metaPath);
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
```

Mainly we extracted methods to be more direct in our communication. For example, the first conditional statement used to use the `is_dir` function from PHP's standard library and then checked whether the result was false. Now, it's more declarative by saying, "Did not find the content public root."

The first extraction was just to take the code that was there and create a function:

```php
// functions.php
function found_content_public_root(string $contentPublicRoot): bool
{
  return is_dir($contentPublicRoot);
}
```

If we used this in our code, it would look something like this:

```php
if (found_content_public_root($contentPublicRoot) === false) {}
```

Since we're looking for the opposite, we created another function representing the opposite:

```php
// functions.php
function did_not_find_content_public_root(string $contentPublicRoot): bool
{
    return ! found_content_public_root($contentPublicRoot);
}
```

We also extracted removing the trailing slash from a requested URL:

```php
// functions.php
function directory_path_from_requested_path(
  string $requestedPath
): string {
  if (str_ends_with($requestedPath, '/')) {
    $requestedPath = substr($requestedPath, 0, -1);
  }
  return $requestedPath;
}
```

And we have two meta-related functions now:

```php
// functions.php
function meta_for_file_path(
  string $metaPath
): \StdClass|false {
    if (is_file($metaPath) === false) {
        return false;
    }

    $json = file_get_contents($metaPath);
    if ($json === false) {
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
    return $root . convert_requested_uri_to_file_path($requestedPath) . '/meta.json';
}
```

For the Amos-style approach, there are two main reasons we want to extract methods:

1. To improve readability and communicate intent.
2. If a similar string of logic is used more than twice.

### Internal server error (500)

A lot of what we used for the Page not found (404) will be used for the Internal server error (500). The big difference is that we won't know whether we can run anything. Therefore, we'll make the Internal server error (500) page standalone HTML.

Any styling to make it look and feel like your site will be part of the HTML; no using external files. We won't include any navigation. The messaging needs to indicate that something seriously wrong happened and that the user could potentially just refresh their browser, and all will be right with the world.

We'll add the following to the `.htaccess` file:

```bash
# Handle errors
ErrorDocument 404 /error-404.php
ErrorDocument 500 /error-500.html
```

We'll add the `error-500` file to the `/public` directory:

```bash
/project
├─ /docs
│   └─ /public
│       ├─ meta.json
│       └─ /about
│           └─ meta.json
└─ /site
    ├─ functions.php
    ├─ /partials
    │   ├─ opening.php
    │   └─ closing.php
    └─ /public
        ├─ .htaccess
        ├─ index.php
        ├─ error-404.php
        ├─ error-500.html        
        └─ /about
            └─ index.php
```

### Unsupported method (405)

Generally speaking, this one is completely optional. Chances are your site will support the `get` and `head` methods at a minimum. If someone is trying to `post` or do any other method, they won't be doing it through a browser and won't need a branded page.

## Amos-style environments

Chances are, with all this code shuffling, an error happened. You may not see the errors because your server setup suppresses them by default; this is a good thing for production environments. It's a good thing in production for the security reasons mentioned above.

Let's add the following to the top of the `opening` partial:

```php
<?php
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);

...
```

`ini` is the initializer for PHP. We've asked it to set the `display_errors` and `display_startup_errors` flags to true. And we want PHP to report all errors.

This creates a problem. (Told you every solution tends to create a problem.)

Before deploying to production, we'll want to return these values to `false`. The chances for human error are pretty high.

This introduces the idea of environments, specifically production and local environments. If we're deploying more than once a month or so, we'll want a way for the site to know its context automatically.

There are a few ways to solve this problem. 

### $_ENV

PHP offers a super global variable called [`$_ENV`](https://www.php.net/manual/en/reserved.variables.environment.php). It's an associative array. If your local environment uses a custom domain like `localhost.com:8888`, you could use that in the `opening` script.

```php
if ($_ENV['HTTP_HOST] === 'localhost.com:8888') {
  ini_set('display_errors', true);
  ini_set('display_startup_errors', true);
  error_reporting(E_ALL);
}

...
```

### Configuration file

A popular approach is through environment variables. Try not to be intimidated. It's a file you put somewhere on your local machine that either won't be overwritten or will never wind up on your production server accidentally.

For example, let's say you use FTP to deploy your site. So, every time you deploy, you upload the `project` folder and its contents. We want the environment file to be in the parent directory for the project. This way, the environment file won't be overwritten or added to the server when you upload the `/project` directory. We'll use a PHP file called `config.php` for this example.

```bash
/project_parent
├─ config.php
└─ /project
    ├─ /docs
    └─ /site
```

Let's update the `opening` script:

```php
<?php
// opening.php
$config = __DIR__ . '/../../../config.php';

if (file_exists($config)) {
  require_once $config;
}

...
```

We'll establish where we think the `config` should be. We'll check if it exists. If it does, we'll bring it in. It doesn't need to be on the server yet, so it's not an integral part of our site. (We'll come back to the `opening`.)

Now, let's write the `config`.

```php
<?php
// config.php
define('MY_SITE_ENV', 'local');
```

We are defining a constant. As a convention, constants are written in all caps with underscores instead of spaces.

Let's head back to the `opening`:

```php
<?php
// opening.php
$config = __DIR__ . '/../../../config.php';

if (file_exists($config)) {
    require_once $config;
}

// The constant is defined, and 
// the value of the constant is "local".
if (defined('MY_SITE_ENV') and MY_SITE_ENV === 'local') {
  ini_set('display_errors', true);
  ini_set('display_startup_errors', true);
  error_reporting(E_ALL);

} else {
  ini_set('display_errors', false);
  ini_set('display_startup_errors', false);

}

require_once __DIR__ . '/../functions.php';
```

A popular approach along these lines is `.env` file. Conceptually it's the same. However, the full integration is a bit more complex than we need now. Going back to the simplicity principle and not solving problems we don't have, this should be fine.

## Talking to crawlers (`robots.txt`)

This includes search crawlers. We'll create another file in the `/public` directory called `robots.txt`:

```bash
/project
├─ /docs
│   └─ /public
│       ├─ meta.json
│       └─ /about
│           └─ meta.json
└─ /site
    ├─ functions.php
    ├─ /partials
    │   ├─ opening.php
    │   └─ closing.php
    └─ /public
        ├─ .htaccess
        ├─ robots.txt
        ├─ index.php
        ├─ error-404.php
        └─ /about
            └─ index.php
```

Inside the `robots.txt` file, we'll put the following:

```bash
User-agent: *
Allow: /
```

The first line says we want these rules to apply to all robots visiting the site. The second line says we want the robot to be able to visit any public page on the site. (`robots.txt` files can do some [pretty interesting things](https://en.wikipedia.org/wiki/Robots.txt), but they are beyond the scope of our purposes except for the sitemap.)

Now, if search engines find the site and respect the `robots.txt` rules, they can index the pages. We can help them by creating a `sitemap.xml` file.

## Sitemap

If you spend a fair amount of time developing websites, you'll find a protocol for almost anything you want. The principle here is if you find yourself working hard to solve a problem you find solved in other places, chances are there's a protocol you're missing somewhere that will make it easier. (I used to get bit by this all the time, I still get bit on occasion, but not as often.)

Here's the main [sitemaps protocol site](https://www.sitemaps.org/protocol.html) for further reading.

Let's create a `sitemap.xml` file in the `/public` directory:

```bash
/project
├─ /docs
│   └─ /public
│       ├─ meta.json
│       └─ /about
│           └─ meta.json
└─ /site
    ├─ functions.php
    ├─ /partials
    │   ├─ opening.php
    │   └─ closing.php
    └─ /public
        ├─ .htaccess
        ├─ robots.txt
        ├─ sitemap.xml
        ├─ index.php
        ├─ error-404.php
        └─ /about
            └─ index.php
```

Inside the `sitemap.xml` file, we're going to put the following:

```xml
<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>http://yourdomain.com/</loc>
    <lastmod>2023-03-27</lastmod>
  </url>
  <url>
    <loc>http://yourdomain.com/about/</loc>
    <lastmod>2023-03-27</lastmod>
  </url>  
</urlset>
```

Of course, there's a problem.

We'd want to update this file every time we add a new page. Likewise, we'd want to edit this file every time we make a significant change to one of the pages.

One page site updated everything three months or so? Probably not a big deal.

We'll presume you're creating new content and updating existing content regularly.

How can we automate this?

Let's change the `sitemap.xml` to `sitemap.php`. Next, modify the `.htaccess` file to make the server use `sitemap.php` when a user (a robot in this case) visits `http://yourdomain.com/sitemap.xml`:

```
<IfModule mod_rewrite.c>
	RewriteEngine On

	RewriteRule ^sitemap\.xml$ sitemap.php [L]
</IfModule>
```

You want to make sure your host allows the `mod_rewrite` module. If they support platforms like Laravel and WordPress, chances are they do.

The first line is a fail soft check. The second line turns the rewrite engine on. The fourth line tells the server that if someone requests `sitemap.xml`, use the `sitemap.php` script instead. The last line closes the conditional check.

We'll want to get all of the `meta.json` files and loop over them to generate the content. For the `loc` tag, we need to have the full URL. And we'll need a date of when the content was last modified. (Please don't fake this, just be honest, search engines prefer honesty.)

Let's start by setting up the `sitemap.php` script:

```php
// sitemap.php
$config = __DIR__ . '/../../../config.php';

if (file_exists($config)) {
    require_once $config;
}

if (defined('AMOS_SITE_ENV') and AMOS_SITE_ENV === 'local') {
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    error_reporting(E_ALL);

} else {
    ini_set('display_errors', false);
    ini_set('display_startup_errors', false);

}
```

It should feel familiar. It's the top part of the `opening` partial. Let's move this to a separate file called `environment.php` and then update the `opening` and `sitemap` scripts with the following:

```php
// Top of opening.php and sitemap.php
require_once __DIR__ . '/../environment.php';
```

Both the `opening` and the `sitemap` will need to know where the content public root is, so let's put that in the `environment` script and make it a little more robust:

```php
$contentPublicRoot = __DIR__ . '/../docs/public';
if (is_dir($contentPublicRoot) === false) {
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    $message  = '500 Internal Server Error';
    header($protocol . ' ' . $message, true, 500);
    print file_get_contents(__DIR__ . '/public/error-500.html');
    exit();
}
```

The first line establishes the path to the `/docs` directory. The second checks to see if the directory exists and, if it doesn't, sets a response header and makes it the only response with a status code of 500. Then we print the contents of the `error-500` file. The last line tells PHP to stop running all processes.

It would be nice if we could notify the server that we would like it to do whatever it would in the case of an internal server error, but I don't know of a way to do that, and this is the next best thing. So we're taking on the duties of the server, but only for the specific use case that the content public root doesn't exist.

The top of the `opening` looks like this:

```php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../functions.php';

$title = page_title($contentPublicRoot, $_SERVER['REQUEST_URI']);
```

And the top of `sitemap` looks like this:

```php
require_once __DIR__ . '/../environment.php';
```

Let's build this thing!

(If you're interested in the building process, check out the [sitemap iterations article](./sitemap-iterations/).)
