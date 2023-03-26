# 8fold Amos for PHP

Amos is more of an approach to implementing websites than a framework or content management system.

The values and principles are more important than any single implementation. We try to be short on binary decisions and long on flexibility.

With that said, you might run into some strong opinions loosely held.

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


