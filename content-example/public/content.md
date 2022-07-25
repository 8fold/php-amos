# 8fold Amos

This project is a collection of patterns, guidelines, guardrails, and sample code for creating file-based websites with minimal dependencies and opinions. 

The patterns are meant to be language and framework agnostic. The patterns are more important than the implementation.

The sample code uses [PHP](https://www.php.net). 

This project is an example of these patterns and sample code in use. Who can host it on your local machine by pointing a PHP server at the following directory: `site-root/local`.

Defined terms are title-cased and their definitions are available in the glossary.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

- Implementations: 
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

## Folders and files (basics)

Each website MAY have three areas:

1. content (serves content creators),
2. site (serves user interface designers and developers), and
3. source (serves all designers and developers).

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

- The `public` folder within a `content` folder MUST be present to use the sample code and classes found here. It represents content Client-users will be able to see at some point during their interactions with the site.
	- `site-root/local` would be for development on a local machine; to display all errors, for example.
	- `site-root/public` would be where the server is pointed for each request; to hide all errors. (This way, you don't run the risk of accidentally misidentifying an environment and exposing the underlying technology stack used for the site.)
- Each folder under a `site` folder represents an environment with its own configuration. We've found performance improvements (speed and memory) over `.env` files and implementations. *Note: Environment-specific variables that need to remain secret SHOULD use something like [.env](https://www.dotenv.org) files.*
-  
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
