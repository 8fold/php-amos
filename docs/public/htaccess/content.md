# .htaccess file

We don't want to try and take on some common things because the server is the first actor for every message. Think of it like a bouncer at a bar, and the `.htaccess` file is the instructions we've given the bouncer regarding who to let in.

We'll talk about common settings for Amos-style websites. If you'd like more details, check out the [Apache Configuration by Mozilla](https://developer.mozilla.org/en-US/docs/Learn/Server-side/Apache_Configuration_htaccess#custom_error_pagesmessages).

## Deny browsing directories

Let's say you enter a valid [.Uniform Resource Locator](URL) into a browser: `http://8fold.pro/some-folder/`

This will typically point to a `public` folder on the server. Specifically `/public/some-folder/`. By default, the server will return a list of files and folders inside that folder. Think of it like [Finder](https://support.apple.com/en-us/HT201732) on macOS or [File Explorer](https://support.microsoft.com/en-us/windows/find-your-documents-in-windows-5c7c8cfe-c289-fae4-f5f8-6b3fdba418d2) on Windows. Usually, we don't want the user to receive a list of files and folders on our server, we want them to see a rendered webpage, or this would open us up to a security vulnerability.

We can save a `.htaccess` file in the `/public/some-folder/` directory on the server. Then we can add the following to the file:

```bash
# Deny browsing directory
Options -Indexes
```

The first line is a comment. The second line tells the server to look for some type of `index` file. The user problem we're solving here is knowing the name files (and the extensions) stored on our server; that used to be a thing.

```
/public
├─ home.html
└─ about.html
```

Allows us to have two URLs:

```
/home.html
/about.html
```

Users don't need to know what file type we're using. So, `indexes` allow us to do the following:

```
/public
├─ index.html
└─ /about
    └─ index.html
```

Giving us the following URLs instead:

```
/
/about/
```

Notice the trailing slash. This is normal for file-based websites. We want to mimic this behavior. (More on that later.)

A `.htaccess` file is limited to a directory. If a directory exists, and the user requests it, by default, they will either see a list of files and directories or they will receive a forbidden error page (status code 403). However, if a file exists, and the user requests it, they will usually receive the contents of the file. The benefit of letting the server handle direct file requests is that servers often send caching information or compress the files before sending them. This improves the site's responsiveness without you needing to worry about it.

The less processing we need between request and response, the sooner the server can move on to the next request.

For example, let's say we have a `css` directory with a `styles.css` file.

```
/public
└─ /css
    └─ styles.css
```

If the user requests `/css/` in the URL, and we have not denied browsing directories, they will see a list containing the `styles.css` file or a server-generated forbidden page (status code 403). If we have denied browsing directories, the server will go up one directory (`public`) to look for a `.htacess` file, look at the `.htaccess` file and return a 404 error page because it could not locate an `index` file to use.

If the user requests `/css/styles.css`, they will see the contents of that file.

It's worth noting that the requestor isn't always a human. Sometimes you'll link files within a page, and the browser, without human input, becomes the requestor. 

So, the human enters the URL. We send back an [.Hypertext Markup Language](HTML) response. A `link` to the `styles.css` file is inside the HTML. The browser will automatically send another request to get the contents of that file. If we deny access to that file somehow, the browser will receive a forbidden error (403). We don't want that in this case.

## Require trailing slashes

Let's say we have the static website example above with the following file structure:

```
/public
├─ index.html
└─ /about
    └─ index.html
```

When a user enters `/about`, it will become `/about/` with a trailing slash.

Many dynamic websites use what's referred to as a [front controller pattern](https://en.wikipedia.org/wiki/Front_controller). In short, we tell the server to use a specific file (the front controller) to process almost every request.

I say "almost every" because if a user requests a file and the file exists, the server will process the request without intervention from our web application (Sitepoint has a [two-part series on front controllers](https://www.sitepoint.com/front-controller-pattern-1/)).

I'm not here to argue whether front controllers are redundant. This section only looks at what happens when we use the front controller pattern from a URL perspective. 

By default, when using a front controller, when a user enters `/about`, it will stay `/about`. When a user enters `/about/`, it will stay `/about/`. 

If we are going to take on the duties of the server, it's preferred that we match the behavior as much as possible. So, if we want to require a trailing slash when a user requests something other than a file, we would add the following to our `.htacess` file:

```bash
RewriteEngine On
	
# Redirect to trailing slash if not folder or file
RewriteCond %{REQUEST_URI} /+[^\.]+$
RewriteRule ^(.+[^/])$ %{REQUEST_URI}/ [R=301,L]
```

The first line turns on the rewrite engine. The third line is a comment. The fourth line is the condition, and it's looking to see if the URL (request [.Uniform Resource Identifier](URI)) is not a file and doesn't have a trailing slash; if both of those are true, the rewrite rule will execute. The fifth line is the rewrite rule, and will send back a response telling the client (browser) to redirect the user to the URL with a trailing slash and that this is a permanent redirect (301). So, in the future, if the user puts the URL in without the trailing, hopefully, the browser will add the trailing slash before sending the request.

The next bit isn't required. However, it's a good practice as not all servers are configured to rewrite URLs. (If your server isn't configured to rewrite URLs, a front controller most likely won't work; if it did, you'd want to have this logic as part of your application.) As such, we want to confirm our server has the ability to do the thing before we ask it to do the thing. So, we would wrap the rewrite condition and rule in the following way:

```bash
<IfModule mod_rewrite.c>
  RewriteEngine On

  # Redirect to trailing slash if not folder or file
  RewriteCond %{REQUEST_URI} /+[^\.]+$
  RewriteRule ^(.+[^/])$ %{REQUEST_URI}/ [R=301,L]
</IfModule>
```

Line one asks the server if the `mod_rewrite` script is available and, if it is, to execute the pieces between the `IfModule` opening and closing bits.

## Redirect users

You write something. It becomes a URL. You move it. Your user enters the old URL in their browser. They get a page not found error (404). That's a bummer. The content still exists. It just has a different URL.

A "New phone, who dis?" situation.

If you don't want to clutter your file system with files that say, "Redirect them to over here," you can use the `.htaccess` file instead. Redirecting has four parts:

1. declaration,
2. status,
3. old URL, and
4. new URL.

We recommend placing redirects before adding trailing slashes in the `.htaccess` file.

```bash
<IfModule mod_rewrite.c>
  RewriteEngine On

  # Redirect to new URL
  Redirect 301 "/old-slug" "/new-slug"
  Redirect 301 "/old-content" "https://new-domain.com/new-slug"
  
  # Redirect to trailing slash if not folder or file
</IfModule>
```

Even though using `.htaccess` files is slower than using the server configuration file, it's likely faster than spinning up your web application. Regardless, we're talking in milliseconds, and using `.htaccess` most likely won't contribute to slow responses.

## Redirect HTTP to HTTPS

I'll defer to the [United States General Service Administration Chief Information Officer](https://www.cio.gov) website's [HTTPS-Only Standard](https://https.cio.gov); that was a mouthful. In short, HTTPS is more secure than HTTP. There is a performance hit (again in terms of milliseconds). With HTTP, the user enters the URL, the Internet converts the human-friendly domain into the computer-friendly version, and the server processes the request. With HTTPS, there's a verification step.

Going back to the bouncer analogy. HTTPS puts another bouncer ahead of the server. Its only job is to check identification. Are you old enough to be here? Is this the place you are looking for, or did you think it was something else? Okay, cool.

Your host may do this automatically; mine does. I can request a free certificate and enable automatic HTTPS, redirecting all non-HTTPS requests to the HTTPS equivalent. I can disable this feature to pick and choose portions of the site to force HTTPS. (Back in the day, if you weren't authenticating users or accepting credit card payments, most sites didn't use HTTPS.)

To redirect to HTTPS, add the following to the `.htaccess` file in the directory (recommend putting this between redirecting to new URLs and trailing slashes):

```bash
<IfModule mod_rewrite.c>
  RewriteEngine On

  # Redirect to new URL

  # Redirect to HTTPS
  RewriteCond %{HTTPS} !on
  RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}  

  # Redirect to trailing slash if not folder or file
</IfModule>
```

Redirecting to a new URL means, "You're in the wrong place." Redirecting to HTTPS means, "You didn't give a proper handshake." Redirecting to the trailing slash is like, "Let me straighten your shirt a bit."

It's important to note that we want to have as few redirects as possible before the user gets a final response. Each redirect forces a new request. (Like when you call customer service and they keep transferring you to a different department.) If it happens too many times, the browser gives up.

## Limit request methods

Requests have methods (or verbs), and responses have status codes (more on those later). For a full list and descriptions, check out [HTTP request methods by Mozilla](https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods). For our purposes, we're going to talk about three methods:

1. get,
2. head, and
2. post.

In other words:

1. "I'd like to *get* the header and body for this thing."
2. "I'd like to get just the header for this thing."
3. "I'd like to give you (*post*) this thing to do something with."

According to the Apache documentation, a server is required to be able to respond to `get` and `head` requests. With that said, you may not want. Maybe you only want to respond to `get` requests; not recommended. If you decide to mess with the methods the server can respond to, it will likely be to deny `post` requests.  

You're on a social media site, you fill out a form, and you hit submit. You are sending a `post` request. Anyone online can send your web application a `post` request. And, if they know what they're doing and what vulnerabilities might exist, they can do some interesting things, like change the content of an entire page.

If you don't process forms on your site and don't use services that need to post content, then you don't need to accept the `post` method. To turn it off, do the following (recommend this go before any redirects):

```bash
<IfModule mod_rewrite.c>
  RewriteEngine On

  # Allow only the following request methods
  RewriteCond %{REQUEST_METHOD} !^(GET|HEAD) [NC]
  RewriteRule .* - [F,L,R=405]
  Header add Access-Control-Allow-Methods "GET, HEAD"
  Header add Allow "GET, HEAD"

  # Redirect to new URL

  # Redirect to HTTPS

  # Redirect to trailing slash if not folder or file
</IfModule>
```

The condition lists the allowed methods. The rule will respond with a method that does not allow status (405). The two header lines make sure `get`, and `head` is part of the request.

This is like putting a no solicitation sign on the door before we get to any security at the gates. "Oh, you're only here to give us stuff. We don't accept anything. Please try again with a different intent.
 
## Custom error pages

We've seen a few status codes in the last few sections. Status codes are numbers placed in the header of a response; there are roughly 600 HTTP [status codes](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status). The most common and well-known are 200 and 404. 200 means everything was processed correctly from the server's perspective. 404 means that whatever was requested could not be found. Above we saw 405, which means the method being used by the request is invalid. We also saw 301, meaning the thing you requested used to be here, but we moved it (permanently) over here. We saw 403, which says, "That thing exists, but you're not allowed." The infamous 500 status code also says, "The server experienced an internal issue."

With some of these status codes, your application may not be running, with an internal server error (500), for example. By default, the server will most likely have pages it can return. However, they will be unbranded, in plain text, and the user may not know if they ended up in the wrong place. Custom error pages to the rescue.

Let's add a custom error page for internal server errors (500) by adding a `405.html` in the public directory:

```base
/public
├─ index.html
├─ 500.html
└─ /about
    └─ index.html
```

Now we can add the following to the `.htaccess` file (recommend before the rewrites and after the denying directory browsing):

```bash
# Deny browsing directory

# Custom error pages
ErrorDocument 500 /500.html

<IfModule mod_rewrite.c>
  RewriteEngine On

  # Allow only the following request methods

  # Redirect to new URL

  # Redirect to HTTPS

  # Redirect to trailing slash if not folder or file
</IfModule>
```

Because we don't know what type of server error we're experiencing, we want the `500.html` page to be completely self-contained. And we can create one of these for each error we could face.

Note how this doesn't redirect the user to a different URL. Instead, if this error occurs, we'll just take the content of the specified document and send that as our response.

## Front controllers

Last, and possibly least, depending on how you decide to roll with things, front controllers. In short, and if you didn't follow the links in the require trailing slashes section, we're going to tell the server, "Before you give up or send a response, send the request to this file."

That's it. 

This should probably be the last thing in the `.htaccess` file, as the server usually goes top-down. That's why we have the most impactful things at the top.

```bash
# Deny browsing directory

# Custom error pages

<IfModule mod_rewrite.c>
  RewriteEngine On

  # Allow only the following request methods

  # Redirect to new URL

  # Redirect to HTTPS

  # Redirect to trailing slash if not folder or file
  
  # Send Requests To Front Controller
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^ index.php [L]  
</IfModule>
```
