<?php require_once __DIR__ . '/../../partials/opening.php'; ?>
<h1 id="roll-your-own">Roll your own</h1>
<p>If rolling your own sounds like crazy talk, there are two principles Amos-style approaches use to reduce the perceived burden:</p>
<ol>
<li>Don&#39;t solve problems you don&#39;t have yet.</li>
<li>Maximize the amount of work not done.</li>
</ol>
<p>If you have one page of <a href="HTML">.Hypertext Markup Language</a> that links folks to other places on the Internet, you&#39;re probably done. As the proliferation of social media and content sites increased, some startups did just this. You have one domain you send people to, and that one site lets people know where to consume your content. The audience chooses where to interact with you from a list of options. If you decide to leave a platform, just deactivate the account and remove the link from the one-page site.</p>
<p>When developing custom content management systems, two areas required the most work and caused the most errors for me:</p>
<ol>
<li>Database connection, queries, and migrations.</li>
<li>Authentication, site administration, and permissions.</li>
</ol>
<p>How could we abandon the need for all that?</p>
<ol>
<li>Don&#39;t use databases.</li>
<li>Don&#39;t have an administration panel.</li>
</ol>
<p>Or, put a different way, in more positive language:</p>
<ol>
<li>Use files instead of databases.</li>
<li>Use <a href="FTP">.File Transfer Protocol</a> clients or similar to update.</li>
</ol>
<p>The folder structure for a project might look like this:</p>
<pre><code class="language-bash">/project
└─ /site
    └─ /public
        └─ index.html
</code></pre>
<p>The <code>project</code> directory is the root folder you end up in when you connect with the FTP client, the root folder of a Git project, or both. The <code>site</code> directory could hold your templates and should not hold data objects and rules. We&#39;ll point your domain and host to the <code>public</code> directory. </p>
<p>The <code>site</code> directory could live just about anywhere on your server, really. You could also name both folders whatever you want to, though this is a convention in web development. Not here to mandate how to structure and name your files and folders. With that said, as a means of communicating, naming it <code>site</code> can tell someone coming in that this is a website or at least a website adjacent project.</p>
<p>For now, we&#39;ll create a page. Remember that <code>index.html</code> file you created in the <a href="../getting-started/">getting started page</a>? Put that into the <code>public</code> folder. </p>
<p>Open it in a browser. Probably nothing to report home about.</p>
<p>Let&#39;s create another directory called <code>about</code> and put an <code>index.html</code> file there.</p>
<pre><code class="language-bash">/project
└─ /site
    └─ /public
        ├─ index.html
        └─ /about
            └─ index.html
</code></pre>
<p>Don&#39;t forget to update this new page&#39;s <code>title</code> tag. </p>
<p>Let&#39;s edit the root page (<code>/public/index.html</code>) and link it to the about page (<code>/public/index.html</code>). In the root page file, add an <code>anchor</code> tag.</p>
<pre><code class="language-html">&lt;a href=&quot;./about/&quot;&gt;About&lt;/a&gt;
</code></pre>
<p><code>a</code> is short for anchor, <code>href</code> (seems to be) short for hyperlink reference, and &quot;About&quot; is what will be rendered on the screen (the content). </p>
<p>All HTML tags can have attributes. Some can have content. We&#39;re using relative links because this is being run locally without a server to resolve things, and it can get weird if we don&#39;t use relative links.</p>
<p>A relative link starts with <code>./</code> or <code>../</code>. The single dot means from the URL we&#39;re already at and down, and the double dot means going up one part of the URL (up one directory). You could have multiple double dots, just know that each double dot means the parent of the current directory.</p>
<h2 id="the-problem">The problem</h2>
<p>As you continue creating pages, chances are you will notice only a few things change from page to page. In this example, we updated the <code>title</code> tag for the about page, added a link in the home page&#39;s content, and the home page and the about page will be different. Duplicating a folder and making minor changes can become tedious pretty quickly.</p>
<p>Back in the day (the early 90s), making changes to the global aspects of a website was time-consuming, especially as more pages were added.</p>
<p>That&#39;s where template engines came into play.</p>
<p>Before we continue, we&#39;ll need a server that can run PHP scripts to do this. We&#39;ll talk about <a href="../testing-on-a-server/">testing on a server</a> elsewhere to stay on track here.</p>
<p>We&#39;ll presume you have chosen a server environment to continue.</p>
<p>The first step is refactoring this site by changing all the <code>.html</code> file extensions to <code>.php</code>. This is refactoring because our users won&#39;t notice we changed anything.</p>
<p>We&#39;ll refactor the site again by taking the HTML from the <code>doctype</code> declaration down to the opening <code>body</code> tag and putting it into a file called <code>opening</code>. We&#39;ll also take the closing <code>body</code> and <code>HTML</code> tags and put them in a file called <code>closing</code>.</p>
<pre><code class="language-bash">/project
└─ /site
    ├─ opening.php
    ├─ closing.php
    └─ /public
        ├─ index.php
        └─ /about
            └─ index.php
</code></pre>
<p>This introduces a problem we&#39;ll encounter later when discussing the <code>title</code> tag. (Take note of this pattern; solutions often create new problems.) In the home page file, we&#39;ll add a couple of lines.</p>
<pre><code class="language-php">&lt;?php require_once __DIR__ . &#39;/../opening.php&#39;; ?&gt;

&lt;?php require_once __DIR__ . &#39;/../closing.php&#39;; ?&gt;
</code></pre>
<p>The first goes above the content, and the second goes below the content. </p>
<p><code>require_once</code> is a PHP function that says, &quot;I want the content and scripts from this file, and I only want it once.&quot; <code>__DIR__</code> gets a full path to the file containing the script. The dot between the <code>__DIR__</code> and the straight single prime (&#39;) says, &quot;I want you to combine these strings.&quot; We know the double-dot-slash goes up one directory. So, the bit after the first <code>require_once</code> becomes: <code>/the/full/path/to/file/../opening.php</code>.</p>
<p>The home page should work. If you view the page&#39;s source code, you should see the <code>doctype</code> declaration and opening <code>body</code> tags followed by the content and then the closing <code>body</code> and <code>html</code> tags.</p>
<p>Good job!</p>
<p>We&#39;re not done. We&#39;ll want to do the same with the <code>about</code> page. Only the <code>require_once</code> will be different because the <code>about</code> page is one folder down.</p>
<pre><code class="language-php">&lt;?php require_once __DIR__ . &#39;/../../opening.php&#39;; ?&gt;

&lt;?php require_once __DIR__ . &#39;/../../closing.php&#39;; ?&gt;
</code></pre>
<p>There&#39;s a problem.</p>
<p>The <code>about</code> page&#39;s <code>title</code> is most likely incorrect. The <code>title</code> is metadata (and that&#39;s an example of foreshadowing). We&#39;re going to modify the <code>opening</code>, and then we&#39;ll modify the <code>home</code> and <code>about</code> pages.</p>
<p>Inside <code>opening</code>, we&#39;re going to add some PHP:</p>
<pre><code class="language-php">&lt;!doctype html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;title&gt;&lt;?php print $title; ?&gt;&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
</code></pre>
<p>This tells PHP to expect a variable named <code>title</code> and to <code>print</code> it in this spot on the <code>home</code> page. Speaking of the <code>home</code> page,  we&#39;re going to add some more PHP right before the first <code>require_once</code>:</p>
<pre><code class="language-php">&lt;?php
$title = &#39;Your home page title&#39;;

require_once __DIR__ . &#39;/../opening.php&#39;;
?&gt;

&lt;?php require_once __DIR__ . &#39;/../closing.php&#39;; ?&gt;
</code></pre>
<p>We need to do the same thing with the <code>about</code> page.</p>
<pre><code class="language-php">&lt;?php
$title = &#39;About | Your home page title&#39;;

require_once __DIR__ . &#39;/../../opening.php&#39;;
?&gt;

&lt;?php require_once __DIR__ . &#39;/../../closing.php&#39;; ?&gt;
</code></pre>
<p>Notice we prefixed the home page title with the about page title? That&#39;s a web convention. While not necessary, it can help orient users.</p>
<p>As we continue making pages, we must create all these page titles. Wouldn&#39;t it be nice if we could automate that whole thing?</p>
<p>Let&#39;s refactor again.</p>
<h2 id="amos-style-page-title">Amos-style page title</h2>
<p>Let&#39;s talk conventions for a second:</p>
<ol>
<li>Metadata is stored in metadata files. We&#39;ll use <code>meta.json</code> using <a href="https://en.wikipedia.org/wiki/JSON">JSON</a>.</li>
<li>(The one we already know.) We stack titles to help orient users.</li>
<li>Metadata files are stored in a separate directory relative to the <code>public</code> directory.</li>
</ol>
<p>We&#39;ll create a <code>docs</code> directory, the content root. The <code>docs</code> directory will contain a <code>public</code> directory, the content public root. This is still refactoring because our users aren&#39;t impacted by the change. Inside the <code>/docs/public</code> directory, we&#39;ll match the <code>/site/public</code> directory structure. Placing a <code>meta.json</code> file into the <code>/docs/public</code> and <code>/docs/public/about</code> directories.</p>
<pre><code class="language-bash">/project
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
</code></pre>
<p>Let&#39;s move the <code>opening</code> and <code>closing</code> scripts into a <code>partials</code> directory and create a <code>functions</code> script.</p>
<pre><code class="language-bash">/project
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
</code></pre>
<p>We&#39;ll need to update the <code>require_once</code> paths (this is for the <code>about</code> page, but the <code>home</code> page will be similar):</p>
<pre><code class="language-php">&lt;?php
$title = &#39;About | Your home page title&#39;;

require_once __DIR__ . &#39;/../../partials/opening.php&#39;;
?&gt;

&lt;?php require_once __DIR__ . &#39;/../../partials/closing.php&#39;; ?&gt;
</code></pre>
<p>We only added the new <code>/partials</code> directory.</p>
<p>The <code>functions</code> script will start out looking like this:</p>
<pre><code class="language-php">&lt;?php
function page_title(): string
{

}
?&gt;
</code></pre>
<p>What needs to happen to make this work?</p>
<ol>
<li>We need to know where to find the <code>meta.json</code> files.</li>
<li>We&#39;ll need to know where the user is on the site.</li>
<li>We&#39;ll need to return a &quot;concatenated&quot; string.</li>
</ol>
<p>With the Amos-style approach, we tend to favor dependency injection, a fancy way of saying, &quot;Give the thing the information it needs to do its job without knowing a lot about its context.&quot; It should be able to do what needs doing without constantly reaching outside its &quot;scope&quot;.</p>
<p>So, we&#39;ll pass in the path to our <code>/docs/public</code> directory. We&#39;ll also give it the path the user is requesting. </p>
<p>Doing things this way is one reason the Amos style doesn&#39;t need to restrict where the roots of things are. Now the function looks like this.</p>
<pre><code class="language-php">&lt;?php
function page_title(
  string $contentPublicRoot,
  string $requestedPath
): string {

}
?&gt;
</code></pre>
<p>To make the <code>home</code> page title appear, the <code>page_title</code> function looks like this:</p>
<pre><code class="language-php">&lt;?php
function page_title(
  string $contentPublicRoot,
  string $requestedPath
): string {
  if (is_dir($contentPublicRoot) === false) {
    return &#39;&#39;;
  }

  if ($requestedPath === &#39;/&#39;) {
    $requestedPath = &#39;&#39;;
  }

  $metaPath = $contentPublicRoot . $requestedPath . &#39;/meta.json&#39;;
  if (is_file($metaPath) === false) {
    return &#39;&#39;;
  }

  $json = file_get_contents($metaPath);
  if ($json === false) {
    return &#39;&#39;;
  }

  $meta = json_decode($json);
  if ($meta === false) {
    return &#39;&#39;;
  }

  if (property_exists($meta, &#39;title&#39;) === false) {
    return &#39;&#39;;
  }

  $title = $meta-&gt;title;

  return $title;
}
?&gt;
</code></pre>
<p>The Amos style favors <a href="https://quinngil.com/2018/11/04/uobjects-if-only-as-a-guard-clause/">guard clauses</a> and soft failures. A guard clause says, &quot;I didn&#39;t get what I wanted, so I&#39;m going to bail.&quot; Soft failure says, &quot;Even though I didn&#39;t get what I want, it&#39;s no reason to blow everything up.&quot; We consistently check that we can continue as expected; if we can&#39;t, we return an empty string.</p>
<p>If we look at the home page, the correct page title should appear. The incorrect page title should appear if we look at the about page. We need to modify the <code>page_title</code> function.</p>
<p>Modifying the <code>page_title</code> function for the <code>about</code> page looks like this (with comments):</p>
<pre><code>&lt;?php
function page_title(
    string $contentPublicRoot,
    string $requestedPath
): string {
  // Does the content public root directory exist?
  if (is_dir($contentPublicRoot) === false) {
    return &#39;&#39;;
  }

  // If the requested path ends in a forward slash, remove it.
  if (str_ends_with($requestedPath, &#39;/&#39;)) {
    $requestedPath = substr($requestedPath, 0, -1);
  }

  // Convert the requested path into an array of parts based on forward slashes.
  $pathParts = explode(&#39;/&#39;, $requestedPath);

  // Prepare a container for the titles we find.
  $titles = [];

  // As long as there is a path part to examine, do so.
  while (count($pathParts) &gt; 0) {
    // Convert the array of parts back to a string.
    $pathToCheck = implode(&#39;/&#39;, $pathParts);

    $metaPath = $contentPublicRoot . $pathToCheck . &#39;/meta.json&#39;;
    if (is_file($metaPath) === false) {
      // Remove the last path part.
      array_pop($pathParts);

      // Skip to the next loop; I wish this were called skip instead of continue.
      continue;
    }

    // We know the file exists; let&#39;s open it.
    $json = file_get_contents($metaPath);
    if ($json === false) {
      array_pop($pathParts);
      continue;
    }

    // We know we were able to get the content of the file;
    // let&#39;s try to convert it to an object. If we couldn&#39;t
    // convert the JSON to an object, skip.
    $meta = json_decode($json);
    if ($meta === false) {
      array_pop($pathParts);
      continue;
    }

    // If the object doesn&#39;t have a title property, skip.
    if (property_exists($meta, &#39;title&#39;) === false) {
      array_pop($pathParts);
      continue;
    }

    // We know the object has a title property.
    // Add the title to the collection of titles.
    $titles[] = $meta-&gt;title;

    // Remove the last path part.
    array_pop($pathParts);
  }

  // Combine all the titles together with a pipe separating them.
  return implode(&#39; | &#39;, $titles);
}
?&gt;
</code></pre>
<p>Believe it or not, we didn&#39;t change all that much. The guard conditions are pretty much the same. The subsequent action is different; pop off the last path part in the collection of path parts, so we don&#39;t get stuck in an infinite loop and skip (<code>continue</code>) to the next iteration of the loop—the loop equivalent of returning early. Instead of returning the title once found, we store it in an array. </p>
<p>The question is, where do we call this from?</p>
<p>I decided to call it from the <code>opening</code> partial, which now looks like this:</p>
<pre><code class="language-php">&lt;?php
require_once __DIR__ . &#39;/../functions.php&#39;;

$title = page_title(
  __DIR__ . &#39;/../../docs/public&#39;,
  $_SERVER[&#39;REQUEST_URI&#39;]
);
?&gt;
&lt;!doctype html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;title&gt;&lt;?php print $title; ?&gt;&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
</code></pre>
<p>This means the top of the <code>home</code> page is back to looking like this:</p>
<pre><code class="language-php">&lt;?php require_once __DIR__ . &#39;/../partials/opening.php&#39;; ?&gt;
</code></pre>
<p>As we build new pages, we only need to include the <code>opening</code> and <code>closing</code> and create the <code>meta.json</code> file with the <code>title</code> property, which looks like this for the <code>home</code> page:</p>
<pre><code class="language-json">{
  &quot;title&quot;: &quot;Your home page title&quot;
}
</code></pre>
<p>And looks like this for the <code>about</code> page:</p>
<pre><code class="language-json">{
  &quot;title&quot;: &quot;About&quot;
}
</code></pre>
<p>Now we can change any page title once, and it updates everywhere.</p>
<p>We only made this automation <em>after</em> recognizing:</p>
<ol>
<li>creating pages is something we&#39;re going to do often;</li>
<li>duplicating the opening and closing of the pages limits us in terms of time, maintenance, and enhancing; and</li>
<li>the desire to have consistency in page titles (typos happen).</li>
</ol>
<p>Next, we&#39;ll discuss some <a href="../subtle-things/">subtle things with great impact</a>.</p>
<?php require_once __DIR__ . '/../../partials/closing.php'; ?>
