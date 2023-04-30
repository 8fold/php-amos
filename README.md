# 8fold Amos for PHP

This project is a collection of patterns and sample code for creating flat-file PHP-based websites with minimal dependencies and opinions. The patterns are more important than the implementation.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

- Implementations SHOULD favor separating metadata, content, rendering, and logic.
	- Metadata SHOULD favor JSON over YAML.
	- Content SHOULD favor Markdown over HTML.
	- Rendering SHOULD favor the system language over HTML.
	- Logic SHOULD favor low-level solutions over exhaustive frameworks.
- Implementations SHOULD favor local scope over global; developers and content creators SHOULD NOT need to modify multiple locations to accomplish things.
- Implementations SHOULD favor taking advantage of, not replacing, the server itself. (The implementation found here uses Apache, however, similar capabilities exist on other server stacks.)
- Implementations SHOULD favor simplicity over complexity; see [Pragmatic Dave's Razor](https://pragdave.me/blog/2014/03/04/time-to-kill-agile.html#back-to-the-basics).
- Paths SHOULD NOT use trailing slashes.
- Website URLs SHOULD NOT display site filenames (`index`) or extensions  (`.html`, `.php`, and so on) and SHOULD use trailing slash to mimic static sites (`https://domain.com/page/` NOT `https://domain.com/page`).

## Installation

1. Clone the repository.
2. Add to project, or, copy, paste, and modify the code in your own classes.

## Folders and files (basics)

Each website MAY have three areas:

1. content,
2. site, and
3. source.

Each site SHOULD have its own content folder. Each site SHOULD have two folders; one for local development and the other for pointing the domain. Each site MAY have only one source folder. A baseline Amos project, MAY look something like this:

```bash
.
├── content-root/
│   └── public
├── site-root/
│   ├── local
│   └── public
└── src
```

While not required, we RECOMMEND prefixing content folders with `content-`, site folders with `site-`, and using the naming convention for your preferred stack to name the folder where source code lives. (In PHP this is `src`.)

### Content directories

*Usually the most frequently changed.*

Details for each page of the website exist within the content directory. Each page MAY have a metadata file, a content file, and any other associated content within the folder representing that page:

```bash
.
├── content-root/
│   └── public/
│       ├── content.md
│       ├── meta.json
│       └── sub-page/
│           ├── content.md
│           └── meta.json
└── ...
```

Note: The content folder(s) MAY NOT be at the same level as the site and source folders. Further, there MAY be more than 1.

The `content.md` is a plain text file that can operate independently from the website and rendering. The `meta.json` file holds metadata about the content itself and SHOULD NOT depend on the website implementation.

### Source directories

*Usually changed less often compared to content.*

The code that consumes, manipulates, and renders the content lives here. We RECOMMEND separating data manipulation code from view code; facilitating [MVC](https://en.wikipedia.org/wiki/Model–view–controller), [MVVM](https://en.wikipedia.org/wiki/Model–view–viewmodel), or [MVP](https://en.wikipedia.org/wiki/Model–view–presenter) design patterns.

### Site directories

*Rarely changed.*

This area is the gateway for the server and allows users to configure the environment. It's also the area where we tend to start when making non-content changes by asking two questions:

1. Is this something the server can do for us?
2. If so, do we want it to?

For example, Apache servers have a file called `.htaccess` we can, and do, use to communicate with the server. We would like to redirect a user from one URL to a different URL. We could:

1. Write source code that looks for a `redirect` key in the `meta.json` file where the value is the target URL for the redirect. Write source code that responds with the proper 300 response code, changing the header location value for the browser. This MAY become difficult to change in the future as the number of redirected pages increases; if we decide to change, the name for the key, for example, we need to write more source code to allow for both, or, potentially update a lot of files.
2. Use a framework that has this implementation already in place, thereby, increasing dependencies. This MAY become difficult in the future because the framework developers may modify things in a way that forces us to write more source code or modify multiple files to update to the latest version of the framework.
3. Add one line to the `.htaccess` file.

## Other

{links or descriptions or license, versioning, and governance}
