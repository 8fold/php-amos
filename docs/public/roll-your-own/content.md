# Roll your own

If rolling your own sounds like crazy talk, there are two principles Amos-style approaches use to reduce the perceived burden:

1. Don't solve problems you don't have yet.
2. Maximize the amount of work not done.

If you have one page of [.Hypertext Markup Language](HTML) that links folks to other places on the Internet, you're probably done. As the proliferation of social media and content sites increased, some startups did just this. You have one domain you send people to, and that one site lets people know where to consume your content. The audience chooses where to interact with you from a list of options. If you decide to leave a platform, just deactivate the account and remove the link from the one-page site.

When developing custom content management systems, two areas required the most work and caused the most errors for me:

1. Database connection, queries, and migrations.
2. Authentication, site administration, and permissions.

How could we abandon the need for all that?

1. Don't use databases.
2. Don't have an administration panel.

Or, put a different way, in more positive language:

1. Use files instead of databases.
2. Use [.File Transfer Protocol](FTP) clients or similar to update.

The folder structure for a project might look like this:

```bash
/project
└─ /site
    └─ /public
        └─ index.html
```

The `project` directory is the root folder you end up in when you connect with the FTP client, the root folder of a Git project, or both. The `site` directory could hold your templates and should not hold data objects and rules. We'll point your domain and host to the `public` directory. 

The `site` directory could live just about anywhere on your server, really. You could also name both folders whatever you want to, though this is a convention in web development. Not here to mandate how to structure and name your files and folders. With that said, as a means of communicating, naming it `site` can tell someone coming in that this is a website or at least a website adjacent project.

For now, we'll create a page. Remember that `index.html` file you created in the [getting started page](../getting-started/)? Put that into the `public` folder. 

Open it in a browser. Probably nothing to report home about.

Let's create another directory called `about` and put an `index.html` file there.

```bash
/project
└─ /site
    └─ /public
        ├─ index.html
        └─ /about
            └─ index.html
```

Don't forget to update this new page's `title` tag. 

Let's edit the root page (`/public/index.html`) and link it to the about page (`/public/index.html`). In the root page file, add an `anchor` tag.

```html
<a href="./about/">About</a>
```

`a` is short for anchor, `href` (seems to be) short for hyperlink reference, and "About" is what will be rendered on the screen (the content). 

All HTML tags can have attributes. Some can have content. We're using relative links because this is being run locally without a server to resolve things, and it can get weird if we don't use relative links.

A relative link starts with `./` or `../`. The single dot means from the URL we're already at and down, and the double dot means going up one part of the URL (up one directory). You could have multiple double dots, just know that each double dot means the parent of the current directory.

## The problem

As you continue creating pages, chances are you will notice only a few things change from page to page. In this example, we updated the `title` tag for the about page, added a link in the home page's content, and the home page and the about page will be different. Duplicating a folder and making minor changes can become tedious pretty quickly.

Back in the day (the early 90s), making changes to the global aspects of a website was time-consuming, especially as more pages were added.

That's where template engines came into play.

Before we continue, we'll need a server that can run PHP scripts to do this. We'll talk about [testing on a server](../testing-on-a-server/) elsewhere to stay on track here.

We'll presume you have chosen a server environment to continue.

The first step is refactoring this site by changing all the `.html` file extensions to `.php`. This is refactoring because our users won't notice we changed anything.

We'll refactor the site again by taking the HTML from the `doctype` declaration down to the opening `body` tag and putting it into a file called `opening`. We'll also take the closing `body` and `HTML` tags and put them in a file called `closing`.

```bash
/project
└─ /site
    ├─ opening.php
    ├─ closing.php
    └─ /public
        ├─ index.php
        └─ /about
            └─ index.php
```

This introduces a problem we'll encounter later when discussing the `title` tag. (Take note of this pattern; solutions often create new problems.) In the home page file, we'll add a couple of lines.

```php
<?php require_once __DIR__ . '/../opening.php'; ?>

<?php require_once __DIR__ . '/../closing.php'; ?>
```

The first goes above the content, and the second goes below the content. 

`require_once` is a PHP function that says, "I want the content and scripts from this file, and I only want it once." `__DIR__` gets a full path to the file containing the script. The dot between the `__DIR__` and the straight single prime (\') says, "I want you to combine these strings." We know the double-dot-slash goes up one directory. So, the bit after the first `require_once` becomes: `/the/full/path/to/file/../opening.php`.

The home page should work. If you view the page's source code, you should see the `doctype` declaration and opening `body` tags followed by the content and then the closing `body` and `html` tags.

Good job!

We're not done. We'll want to do the same with the `about` page. Only the `require_once` will be different because the `about` page is one folder down.

```php
<?php require_once __DIR__ . '/../../opening.php'; ?>

<?php require_once __DIR__ . '/../../closing.php'; ?>
```

There's a problem.

The `about` page's `title` is most likely incorrect. The `title` is metadata (and that's an example of foreshadowing). We're going to modify the `opening`, and then we'll modify the `home` and `about` pages.

Inside `opening`, we're going to add some PHP:

```php
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php print $title; ?></title>
  </head>
  <body>
```

This tells PHP to expect a variable named `title` and to `print` it in this spot on the `home` page. Speaking of the `home` page,  we're going to add some more PHP right before the first `require_once`:

```php
<?php 
$title = 'Your home page title';

require_once __DIR__ . '/../opening.php'; 
?>

<?php require_once __DIR__ . '/../closing.php'; ?>
```

We need to do the same thing with the `about` page.

```php
<?php 
$title = 'About | Your home page title';

require_once __DIR__ . '/../../opening.php'; 
?>

<?php require_once __DIR__ . '/../../closing.php'; ?>
```

Notice we prefixed the home page title with the about page title? That's a web convention. While not necessary, it can help orient users.

As we continue making pages, we must create all these page titles. Wouldn't it be nice if we could automate that whole thing?

Let's refactor again.

## Amos-style page title

Let's talk conventions for a second:

1. Metadata is stored in metadata files. We'll use `meta.json` using [JSON](https://en.wikipedia.org/wiki/JSON).
2. (The one we already know.) We stack titles to help orient users.
3. Metadata files are stored in a separate directory relative to the `public` directory.

We'll create a `docs` directory, the content root. The `docs` directory will contain a `public` directory, the content public root. This is still refactoring because our users aren't impacted by the change. Inside the `/docs/public` directory, we'll match the `/site/public` directory structure. Placing a `meta.json` file into the `/docs/public` and `/docs/public/about` directories.

```bash
/project
├─ /docs
│   └─ /public
│       ├─ meta.json
│       └─ /about
│           └─ meta.json
└─ /site
    ├─ opening.php
    ├─ closing.php
    └─ /public
        ├─ index.php
        └─ /about
            └─ index.php
```

Let's move the `opening` and `closing` scripts into a `partials` directory and create a `functions` script.

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
        ├─ index.php
        └─ /about
            └─ index.php
```

We'll need to update the `require_once` paths (this is for the `about` page, but the `home` page will be similar):

```php
<?php
$title = 'About | Your home page title';

require_once __DIR__ . '/../../partials/opening.php';
?>

<?php require_once __DIR__ . '/../../partials/closing.php'; ?>
```

We only added the new `/partials` directory.

The `functions` script will start out looking like this:

```php
<?php
function page_title(): string
{

}
?>
```

What needs to happen to make this work?

1. We need to know where to find the `meta.json` files.
2. We'll need to know where the user is on the site.
3. We'll need to return a "concatenated" string.

With the Amos-style approach, we tend to favor dependency injection, a fancy way of saying, "Give the thing the information it needs to do its job without knowing a lot about its context." It should be able to do what needs doing without constantly reaching outside its "scope".

So, we'll pass in the path to our `/docs/public` directory. We'll also give it the path the user is requesting. 

Doing things this way is one reason the Amos style doesn't need to restrict where the roots of things are. Now the function looks like this.

```php
<?php
function page_title(
  string $contentPublicRoot,
  string $requestedPath
): string {

}
?>
```

To make the `home` page title appear, the `page_title` function looks like this:

```php
<?php
function page_title(
  string $contentPublicRoot,
  string $requestedPath
): string {
  if (is_dir($contentPublicRoot) === false) {
    return '';
  }

  if ($requestedPath === '/') {
    $requestedPath = '';
  }

  $metaPath = $contentPublicRoot . $requestedPath . '/meta.json';
  if (is_file($metaPath) === false) {
    return '';
  }

  $json = file_get_contents($metaPath);
  if ($json === false) {
    return '';
  }

  $meta = json_decode($json);
  if ($meta === false) {
    return '';
  }

  if (property_exists($meta, 'title') === false) {
    return '';
  }

  $title = $meta->title;

  return $title;
}
?>
```

The Amos style favors [guard clauses](https://quinngil.com/2018/11/04/uobjects-if-only-as-a-guard-clause/) and soft failures. A guard clause says, "I didn't get what I wanted, so I'm going to bail." Soft failure says, "Even though I didn't get what I want, it's no reason to blow everything up." We consistently check that we can continue as expected; if we can't, we return an empty string.

If we look at the home page, the correct page title should appear. The incorrect page title should appear if we look at the about page. We need to modify the `page_title` function.

Modifying the `page_title` function for the `about` page looks like this (with comments):

```
<?php
function page_title(
    string $contentPublicRoot,
    string $requestedPath
): string {
  // Does the content public root directory exist?
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

      // Skip to the next loop; I wish this were called skip instead of continue.
      continue;
    }

    // We know the file exists; let's open it.
    $json = file_get_contents($metaPath);
    if ($json === false) {
      array_pop($pathParts);
      continue;
    }

    // We know we were able to get the content of the file;
    // let's try to convert it to an object. If we couldn't
    // convert the JSON to an object, skip.
    $meta = json_decode($json);
    if ($meta === false) {
      array_pop($pathParts);
      continue;
    }

    // If the object doesn't have a title property, skip.
    if (property_exists($meta, 'title') === false) {
      array_pop($pathParts);
      continue;
    }

    // We know the object has a title property.
    // Add the title to the collection of titles.
    $titles[] = $meta->title;

    // Remove the last path part.
    array_pop($pathParts);
  }

  // Combine all the titles together with a pipe separating them.
  return implode(' | ', $titles);
}
?>
```

Believe it or not, we didn't change all that much. The guard conditions are pretty much the same. The subsequent action is different; pop off the last path part in the collection of path parts, so we don't get stuck in an infinite loop and skip (`continue`) to the next iteration of the loop—the loop equivalent of returning early. Instead of returning the title once found, we store it in an array. 

The question is, where do we call this from?

I decided to call it from the `opening` partial, which now looks like this:

```php
<?php
require_once __DIR__ . '/../functions.php';

$title = page_title(
  __DIR__ . '/../../docs/public',
  $_SERVER['REQUEST_URI']
);
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php print $title; ?></title>
  </head>
  <body>
```

This means the top of the `home` page is back to looking like this:

```php
<?php require_once __DIR__ . '/../partials/opening.php'; ?>
```

As we build new pages, we only need to include the `opening` and `closing` and create the `meta.json` file with the `title` property, which looks like this for the `home` page:

```json
{
  "title": "Your home page title"
}
```

And looks like this for the `about` page:

```json
{
  "title": "About"
}
```

Now we can change any page title once, and it updates everywhere.

We only made this automation *after* recognizing the following:

1. creating pages is something we're going to do often;
2. duplicating the opening and closing of the pages limits us in terms of time, maintenance, and enhancing; and
3. the desire to have consistency in page titles (typos happen).

Next, we'll discuss some [subtle things with great impact](../subtle-things/).