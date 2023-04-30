# 8fold Amos for PHP

Amos is more of an approach to implementing websites than a framework or content management system.

The values and principles are more important than any single implementation. We try to be short on binary decisions and long on flexibility.

With that said, you might run into some strong opinions loosely held.

For example, a principle (or core belief) is that making a website is relatively easy. A value is to cater to favor the most constrained.

Every computer operating system comes with some form of a [command line or terminal](https://en.wikipedia.org/wiki/Command-line_interface) for free. Most users don't know what they are, don't want to learn how to use them, and may not realize that it's completely unnecessary (at least for developing and launching a website). This lack of knowledge constraints these users, which isn't bad.

Another principle is that creators should be able to opt into complexity; it should not be a fundamental aspect of participation. Experts (or even the competent) in a field can tend to emphasize all the complexities in nuance, which causes beginners to run away and think they're incapable of participating.

Let's start by creating a website that "runs" locally on your computer.

What you'll need:

1. A plain text editor: All operating systems come with one for free.
	1. For macOS, [TextEdit](https://support.apple.com/guide/textedit/welcome/mac) will do just fine. In the menu, go to Format and select Make Plain Text.
	2. For Windows, [Notepad](https://support.microsoft.com/en-us/windows/help-in-notepad-4d68c388-2ff2-0e7f-b706-35fb2ab88a8c) will do just fine.
	3. Linux is beyond my wheelhouse, and from a cursory glance, there are many to choose from.
2. An internet browser: This doesn't need to be connected to the Internet. There are many to choose from, and one, most likely, came preinstalled on your computer.

That's it.

1. Create a directory called `/my-site` somewhere on you computer; recommend the desktop.
2. Open your plain text editor.
3. Type "Hello, World!"
4. Save the file in the `/my-site` directory and name it `index.html`.
5. Open the `/my-site` directory.
6. Double-click the `index.html` file. If it doesn't automatically open in you default browser:
	1. Open your browser.
	2. Got to file.
	3. Click open.
	4. Find the `/my-site` directory.
	5. Click on the `index.html` file.
	6. Click open.

You should see the words "Hello, World!" in the browser.

Congratulations!

Another set of principles is:

1. There are very few unique problems.
2. There are infinite possible solutions to a given problem.
3. Almost every solution introduces at least one new problem.

Let's add another paragraph to the `index.html` file:

```html
Hello, World!

How are you?
```

Refresh your browser.

You'll probably see something like this:

```bash
Hello, World! How are you?
```

That's not what we wanted.

It's happening because the browser does its best to interpret your intent. Browsers tend to ignore whitespace characters. We need a way to communicate our intent to the browser (or client, if you're fancy).

Enter [.Hypertext Markup Language](HTML), specifically the paragraph element.

We want to tell the browser when the paragraph starts and ends. Let's modify the `index.html` file again:

```html
<p>Hello, World!</p>

<p>How are you?</p>
```

Refresh your browser, and you should see something like this:

```bash
Hello, World! 

How are you?
```

Every HTML element (sometimes referred to as tags) starts with a [left angle bracket](https://en.wikipedia.org/wiki/Bracket) (less than sign), followed by one or more letters, and followed by a right angle bracket. This is the opening tag.

The content may be plain text or other HTML elements for elements that may contain content. For these elements, we want to create a closing tag. Closing tags are very similar to their opening tag. The only difference is adding a [forward slash](https://www.thesaurus.com/e/grammar/slash/) after the left angle bracket.

Right now, our `index.html` file is not what we would call a well-formed HTML document. It works because most browser developers try hard to make pages work.

A well-formed HTML document starts with a document-type declaration. It's an element that won't be rendered on screen. In the early 2000s, there were lots of these available. Since around 2008, it became simplified with the [release of HTML5](https://en.wikipedia.org/wiki/HTML5). Let's add a document-type declaration to the top of `index.html`:

```html
<!doctype html>
<p>Hello, World!</p>

<p>How are you?</p>
```

Refresh your browser, and nothing should change.

Notice that you can't *see* the document-type declaration on screen? That's because it's a hidden element.

Still not well-formed, but we're getting there.

We need what's called a root element. A root element is an HTML element containing (wrap) all the other content, plain text, or other elements. For HTML pages, the root element is `html`:

```html
<!doctype html>
<html>
  <p>Hello, World!</p>

  <p>How are you?</p>
</html>
```

This tells the browser that our HTML will start at the opening tag and end at the closing tag.

Refresh your browser. Still, nothing changed, but it works, and that's the important part in this context.

Still not well-formed.

Something we value in web development is separating content from [metadata](https://en.wikipedia.org/wiki/Metadata). We do this at the page level with two more elements; `head` and `body`. `head` is for metadata, and `body` is for content.

Our paragraphs are content, so let's wrap them in a `body` element:

```html
<!doctype html>
<html>
  <body>
    <p>Hello, World!</p>

    <p>How are you?</p>
  </body>
</html>
```

Refresh the browser. Still works. Good times.

Still not well-formed because we're missing a piece of required metadata; the `title`.

Even though the order of these elements doesn't matter, by convention, we put metadata at the top of a document. Let's add the title "My site" inside a `title` element, wrapped in the `head` element.

```html
<!doctype html>
<html>
  <head>
    <title>My site</title>
  </head>
  <body>
    <p>Hello, World!</p>

    <p>How are you?</p>
  </body>
</html>
```
  
Refresh the browser. Something changed.

If you look at the, it might not be obvious. You might need to hover your mouse over the window or tab you have the page in or start your screen reader from the top.

This is a well-formed HTML page because:

1. It starts with a document-type declaration.
2. It has a root `html` element.
3. It has a `head` element with a `title` element; technically could be empty and still well-formed.
4. It has a `body` element.
5. All elements are opened and closed correctly.

The [w3c](https://www.w3.org) is a nonprofit organization that creates recommendations for the web. They offer a [markup validation service](https://validator.w3.org/#validate_by_input) that was all the rage in the early 2000s; we had badges and everything to let people know you were complying with the recommendations. If you go there and select "Validate by Direct Input" and paste the contents of your `index.html` file, it will come up with no errors. It will have a warning, though. Errors are bigger than warnings.

We do get a warning:

> Consider adding a lang attribute to the html start tag to declare the language of this document.

An element may contain any number of attributes. An attribute is a fancy way of saying "Key-value pair", which means that this thing translates to this other thing. Attributes come after the element text of the opening tag and end before the right angle bracket. 

There are standards (shocker) regarding how to note which language you're referring to or using. I'm in the United States and using English of the United States; this is noted as "en-US". So, let's add a language to the `index.html` file. We'll add it to the `html` element; however, we could add it to any element:

```html
<!doctype html>
<html lang="en-US">
  <head>
    <title>My site</title>
  </head>
  <body>
    <p>Hello, World!</p>

    <p>How are you?</p>
  </body>
</html>
```

Our HTML page is well-formed (no errors) and has no warnings.

The Internet is built on atomic concepts that are repeated and combined to do some amazing things. The construction of elements is a perfect example, and we've already talked about the atomic concepts related to them.






The right angle bracket will be followed by plain text or more HTML elements for elements that accept content. For content elements, we'll need to close them by typing a left angle bracket, followed by a , followed by the same text in the opening, followed by a right angle bracket.











For example, Amos-style implementations should avoid duplicating what we get from the server for free. The related value here is simplicity. The related principle is that less code means fewer bugs. 

The presumption is you're hosting your site on an [Apache](https://httpd.apache.org) or Apache-like server, and you do not have direct access to the configuration files for the server; shared hosting and virtual private servers as opposed to dedicated servers.

For Amos, the [.Uniform Resource Locator](URL) is the center of the universe. A user enters a URL into a client (usually a browser). The client converts this to a request, a type of message. The server receives the request, and if the server can process it directly, we should let it. If sever can't handle it directly, it will forward the message to the application. The message has metadata (headers) and optional content (body).

Apache uses a [`.htaccess`](https://httpd.apache.org/docs/current/howto/htaccess.html#page-header) file to allow you to configure how the server should handle certain things. While not preferred, using the `.htaccess` file is pretty common; [Slim](https://www.slimframework.com), [Laravel](https://laravel.com), [WordPress](https://wordpress.org), and similar frameworks and systems. ([Read more about `.htaccess` and Amos](./htaccess)).

Amos-style websites separate things into four major areas:

1. content,
2. metadata,
3. logic, and
4. views.

Content is usually in the form of Markdown. Metadata is typically [.JavaScript Object Notation](JSON). Logic is where rules are applied (usually to the content and metadata). Views render the result of logic being applied to the content and metadata.

Many popular frameworks either require a specific project setup or convention causes a certain project setup to become dominant. For example, you might see the following:

```bash
/app
├─ /models
├─ /views
├─ /controllers
├─ /migrations
├─ /config
└─ /public
```

From this perspective, I have no idea what type of application this is; except that it feels like a contemporary [.Model View Controller](MVC) web application. However, it tells me nothing about the architecture or domain of the application; what user problem am I trying to solve? To get that level of understanding, I need to start opening directories.

Notice that the content and metadata about that content aren't represented here? That's because it's often stored in a database, which might be fine. And it's one more place I need to go to understand what's happening. There's a valid reason for this to be the case.

Having specified locations for certain things allows the framework to know the location of things. Even if those locations aren't, strictly speaking, required. It also helps with interoperability; developers can quickly get their bearings from one project to another.

Because Amos isn't a framework or system, you can pretty much do whatever you want to do regarding how you organize your files and folders. 

[Let's get started](./getting-started)!


