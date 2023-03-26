<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>8fold Amos for PHP</title>
    </head>
    <body>
<h1 id="8fold-amos-for-php">8fold Amos for PHP</h1>
<p>Amos is more of an approach to implementing websites than a framework or content management system.</p>
<p>The values and principles are more important than any single implementation. We try to be short on binary decisions and long on flexibility.</p>
<p>With that said, you might run into some strong opinions loosely held.</p>
<p>For example, Amos-style implementations should avoid duplicating what we get from the server for free. The related value here is simplicity. The related principle is that less code means fewer bugs. </p>
<p>The presumption is you&#39;re hosting your site on an <a href="https://httpd.apache.org">Apache</a> or Apache-like server, and you do not have direct access to the configuration files for the server; shared hosting and virtual private servers as opposed to dedicated servers.</p>
<p>For Amos, the <a href="URL">.Uniform Resource Locator</a> is the center of the universe. A user enters a URL into a client (usually a browser). The client converts this to a request, a type of message. The server receives the request, and if the server can process it directly, we should let it. If sever can&#39;t handle it directly, it will forward the message to the application. The message has metadata (headers) and optional content (body).</p>
<p>Apache uses a <a href="https://httpd.apache.org/docs/current/howto/htaccess.html#page-header"><code>.htaccess</code></a> file to allow you to configure how the server should handle certain things. While not preferred, using the <code>.htaccess</code> file is pretty common; <a href="https://www.slimframework.com">Slim</a>, <a href="https://laravel.com">Laravel</a>, <a href="https://wordpress.org">WordPress</a>, and similar frameworks and systems. (<a href="./htaccess">Read more about <code>.htaccess</code> and Amos</a>).</p>
<p>Amos-style websites separate things into four major areas:</p>
<ol>
<li>content,</li>
<li>metadata,</li>
<li>logic, and</li>
<li>views.</li>
</ol>
<p>Content is usually in the form of Markdown. Metadata is typically <a href="JSON">.JavaScript Object Notation</a>. Logic is where rules are applied (usually to the content and metadata). Views render the result of logic being applied to the content and metadata.</p>
<p>Many popular frameworks either require a specific project setup or convention causes a certain project setup to become dominant. For example, you might see the following:</p>
<pre><code class="language-bash">/app
├─ /models
├─ /views
├─ /controllers
├─ /migrations
├─ /config
└─ /public
</code></pre>
<p>From this perspective, I have no idea what type of application this is; except that it feels like a contemporary <a href="MVC">.Model View Controller</a> web application. However, it tells me nothing about the architecture or domain of the application; what user problem am I trying to solve? To get that level of understanding, I need to start opening directories.</p>
<p>Notice that the content and metadata about that content aren&#39;t represented here? That&#39;s because it&#39;s often stored in a database, which might be fine. And it&#39;s one more place I need to go to understand what&#39;s happening. There&#39;s a valid reason for this to be the case.</p>
<p>Having specified locations for certain things allows the framework to know the location of things. Even if those locations aren&#39;t, strictly speaking, required. It also helps with interoperability; developers can quickly get their bearings from one project to another.</p>
<p>Because Amos isn&#39;t a framework or system, you can pretty much do whatever you want to do regarding how you organize your files and folders. </p>
<p><a href="./getting-started">Let&#39;s get started</a>!</p>
    </body>
</html>
