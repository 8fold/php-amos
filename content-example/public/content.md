# 8fold Amos

This project is a collection of patterns, guidelines, guardrails, and sample code for creating file-based websites.

The patterns are meant to be language and framework agnostic. The patterns are more important than the implementation.

The sample code uses [PHP](https://www.php.net).

This project is an example of these patterns and sample code in use. Who can host it on your local machine by pointing a PHP server at the following directory: `site-root/local`.

Defined terms are title-cased and their definitions are available in the glossary.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

- Implementations:
	- SHOULD favor dependency injection despite singletons and globals being available.
	- SHOULD favor separating metadata, content, rendering, and logic.
		- Metadata SHOULD favor JSON over YAML.
		- Content SHOULD favor Markdown over HTML.
		- Rendering SHOULD favor the system language over HTML.
		- Logic SHOULD favor low-level solutions over exhaustive frameworks.
	- SHOULD favor local scope over global; developers and content creators SHOULD NOT need to modify multiple locations to change something.
	- SHOULD favor taking advantage of, not replacing, the server. (The implementation found here uses Apache, however, similar capabilities exist on other server stacks.)
	- SHOULD favor simplicity over complexity; see [Pragmatic Dave's Razor](https://pragdave.me/blog/2014/03/04/time-to-kill-agile.html#back-to-the-basics).
- Website URLs:
	- SHOULD NOT display site filenames (`index`) or extensions  (`.html`, `.php`, and so on) and SHOULD use trailing slash to mimic static sites (`https://domain.com/page/` NOT `https://domain.com/page`).
	- SHOULD display a trailing slash when viewing a content page; replicating static sites.

For the web, HTTP messages (described in [RFC 7230](https://datatracker.ietf.org/doc/html/rfc7230), [RFC 7231](https://datatracker.ietf.org/doc/html/rfc7231), and [RFC 3986](https://datatracker.ietf.org/doc/html/rfc3986)) use the following flow:

1. User interacts with a Site using a Client; a browser, for example.
2. Client sends a message (Request) to a Server for resolution.
3. Server (along with Site code) receives the Request.
4. Server (and Site) resolve the Request.
5. Server returns a message (Response).
6. Client receives the Response.
7. Client resolves the Response.
8. Client updates the interface accordingly.
9. Go to 1.

However, this concept and flow can be seen in many areas of life.

## Principles

Principles represent our guardrails. They are more rigid than values.

1. The server can respond faster than our application.

## Practices

1. For each feature, implementations SHOULD start by asking if the server can do what we are wanting to do; delivering site assets such as style sheets and similar, for example. (Principle: 1)

## Information architecture (basics)

We RECOMMEND dividing things into three areas: content, server, and source. Generally, a separate folder for each.

The content area represents what would typically found in a database, serves content creators, and typically changes more than the other areas. The server area gives the server a place to start or respond and typically changes less often than the other two areas; where the server will be pointed as the root of the site. The source area is where code for rendering the site is housed and typically changes less often than the content area.

This separation affords us the opportunity to decouple all three areas entirely from one another.

We RECOMMEND:

- The content area root folder be prefixed with the word `content-`.
- The server area root folder be prefixed with the word `site-`.
- The source are root folder be named in the tradition of the language or framework; for example, PHP projects typically use `src`.

```bash
.
├── content-root/
├── site-root/
└── src
```

Within the content and server areas, we RECOMMEND creating a `public` folder. In the content area this affords implementers the ability to add other folders to store files from content creators that operate outside the standard URL-page relationship; system alerts, for example. In the site area this affords implementers the ability to create environment folders without the overhead sometimes seen with configuration (`.env` files) unless necessary for the security of the site.

```bash
.
├── content-root/
│   └── public
├── site-root/
│   └── public
└── src
```

To use the sample code found in this project, which is used to render and maintain this site, we presume the existence of the `public` folders.

The server for the website SHOULD be pointed to the `public` folder in the site area folder. For local development, we RECOMMEND creating a duplicate folder called `local` and pointing local servers (virtual machines) to that folder.

```bash
.
├── content-root/
│   └── public
├── site-root/
│   └── local
│   └── public
└── src
```

This allows implementers to closely mirror their production environments based on the contents of those folders.

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

- We RECOMMEND that content folders without a `meta.json` file are considered unpublished by scripts that would take that into account; `SiteMap` example, for example.

### Source directories

*Usually changed less often compared to content.*

The code that consumes, manipulates, and renders the content lives here. We RECOMMEND separating data manipulation code from view code; facilitating [MVC](https://en.wikipedia.org/wiki/Model–view–controller), [MVVM](https://en.wikipedia.org/wiki/Model–view–viewmodel), or [MVP](https://en.wikipedia.org/wiki/Model–view–presenter) design patterns.

```bash
.
├── content-root/
├── site-root/
└── src
│   └── Documents
│   └── Templates
```

### Site directories

*Rarely changed.*

This area is the gateway for the server and allows users to configure the environment. It's also the area where we tend to start when making non-content changes by asking two questions:

1. Is this something the server can do for us?
2. If so, do we want it to?

For example, Apache servers have a file called `.htaccess` we can, and do, use to communicate with the server. We would like to redirect a user from one URL to a different URL. We could:

1. Write source code that looks for a `redirect` key in the `meta.json` file where the value is the target URL for the redirect. Write source code that responds with the proper 300 response code, changing the header location value for the browser. This MAY become difficult to change in the future as the number of redirected pages increases; if we decide to change, the name for the key, for example, we need to write more source code to allow for both, or, potentially update a lot of files.
2. Use a framework that has this implementation already in place, thereby, increasing dependencies. This MAY become difficult in the future because the framework developers may modify things in a way that forces us to write more source code or modify multiple files to update to the latest version of the framework.
3. Add one line to the `.htaccess` file.
