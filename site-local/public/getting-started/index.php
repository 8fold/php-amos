<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Getting started | 8fold Amos for PHP</title>
    </head>
    <body>
<h1 id="getting-started">Getting started</h1>
<p>One of the things I appreciate about the Internet is how easy, relatively speaking, it is to create something without any special hardware or software.</p>
<ol>
<li>Plain text editor available for free on every operating system.<ol>
<li>macOS has <a href="https://support.apple.com/guide/textedit/welcome/mac">TextEdit</a>. You&#39;ll want to <a href="https://support.apple.com/guide/textedit/change-textedit-settings-txted1063/1.17/mac/13.0">change the format to plain text</a>.</li>
<li>Windows has <a href="https://apps.microsoft.com/store/detail/windows-notepad/9MSMLRH6LZF3?hl=en-us&gl=us">Notepad</a>.</li>
</ol>
</li>
<li>Open a new file and type &quot;Hello, World!&quot; on the first line.</li>
<li>Save the file as something with a &quot;.html&quot; extension.<ol>
<li>ex. &quot;index.html&quot;</li>
</ol>
</li>
<li>Open that file in a web browser.</li>
<li>You should see &quot;Hello, World!&quot;</li>
</ol>
<p>You can create an entire site on your computer (called &quot;local&quot;). There are some drawbacks here, most notably for our purposes, which is that if you want multiple paragraphs, you&#39;ll need some <a href="HTML">.Hypertext Markup Language</a>.</p>
<ol>
<li>Open the file.</li>
<li>Change &quot;Hello, World!&quot; to <code>&lt;p&gt;Hello, World!&lt;/p&gt;</code>.<ol>
<li>Add a second paragraph: <code>&lt;p&gt;How are you?&lt;/p&gt;</code>.</li>
</ol>
</li>
<li>Save the file.</li>
<li>Refresh your browser.</li>
</ol>
<p><code>&lt;p&gt;</code> means we want to start a paragraph and <code>&lt;/p&gt;</code> is where we want that paragraph to end. HTML as a language ignores most whitespace beyond a single space.</p>
<p>Technically speaking, there are some other requirements, but this is enough to get started on the fundamental concepts of web development. If you&#39;d like to continue expanding your HTML knowledge, I appreciate this <a href="https://html.com">HTML for absolute beginners</a>.</p>
<p>Fundamental concepts:</p>
<ol>
<li>You don&#39;t need special hardware or software to get started.</li>
<li>The Internet is made of plain text; see fundamental concept number one.</li>
<li>Most people only need to know a few HTML tags to make something interesting.</li>
</ol>
<h2 id="from-local-to-global">From local to global</h2>
<p>If you want to journal on your local machine, many solutions allow you to do this without having it visible in a browser. Many of us want others to see what we post online; many solutions exist. Some of us who want to post online would rather do it ourselves; own our data and content.</p>
<p>This is where hosting comes into play.</p>
<p>A host is a computer running server software that allows others to find, connect, and make requests. This could be the computer you use daily, but many of us delegate and outsource that to someone else. There are many options, depending on what you&#39;re going for.</p>
<p>Fundamental concepts:</p>
<ol>
<li>You&#39;ll usually register a human-friendly domain name (think phone number).</li>
<li>You&#39;ll point that human-friendly domain name to a publicly accessible <a href="IP">.Internet Protocol</a> Address; not human-friendly (think <a href="https://support.apple.com/en-us/HT204073">IMEI number</a>).</li>
<li>You&#39;ll somehow upload files and folders to the other computer (host).</li>
</ol>
<p>Domain registration will cost around 20 <a href="USD">.United States Dollars</a> per year. Hosting, the ability to put your files somewhere other people can access them, will cost a few dollars monthly. And in both cases, there are often discounts if you buy multiple years at a time.</p>
<p>I use <a href="https://www.dreamhost.com">Dreamhost</a> and have since about 2005. I started on a <a href="https://www.dreamhost.com/hosting/shared/">shared hosting plan</a>, with multiple websites hosted on a single server. I moved to a <a href="https://www.dreamhost.com/hosting/vps/"><a href="VPS">.Virtual Private Server</a> plan</a> around 2018. (I actively avoid GoDaddy and Network Solutions.)</p>
<p>Most hosting providers offer control panels that will give you <a href="FTP">.File Transfer Protocol</a> to the part of the server that you pay for. There are also standalone applications you can purchase. Your host should have <a href="https://help.dreamhost.com/hc/en-us/articles/360020552911-Logging-into-your-DreamPress-site-via-FTP">instructions on how to connect via FTP</a>.</p>
<p>I&#39;ve used <a href="https://panic.com/transmit/">Transmit by Panic</a> for years when I need to do FTP things. Before that, when I would do development at work, I&#39;d use Notepad and upload files through the host&#39;s website. I have a different workflow and don&#39;t go through FTP too often.</p>
<p>When you upload pages, chances are they should be in their own folder and named <code>index.html</code>. You should be able to go to <code>http://yourdomain.com/</code> and see your main file. And anyone else on the Internet can do the same, at least by default.</p>
<h2 id="html-is-annoying-and-typography-is-hard">HTML is annoying, and typography is hard</h2>
<p>HTML may be easy, relatively speaking. However, it&#39;s pretty annoying. It uses a lot of non-standard characters like the less-than sign, greater-than sign, and so on. We&#39;ve created a lot of ways to simplify its creation.</p>
<p>Specialized text editors will autocomplete tags; I use <a href="https://nova.app">Panic Nova</a> as of this writing. Before that, I used <a href="https://www.sublimetext.com">Sublime Text</a>. For the most part, I use a technology that started in 2004 and has been evolving ever since called <a href="https://daringfireball.net/projects/markdown/">Markdown</a>.</p>
<p>Amos-style sites tend to favor Markdown for the main content, and HTML and <a href="CSS">.Cascading Stylesheets</a> for layout and aesthetics.</p>
<p>One of the things that makes HTML annoying is character encoding and the difference between primes, apostrophes, and opening and closing quotes.</p>
<p>A single prime (′) is not the same as an apostrophe (՚), a left single quote (‘), or a right single quote (’); however, we use the same key on our keyboards for all four. The software we use to see the text may or may not make our choices. Further, the plain text uses a different character, a straight single prime (&#39;). If the browser doesn&#39;t know the character set used, and we use any of these characters in the content could result in a missing character icon or something like this: Iâ€™m.</p>
<p>Amos-style sites tend to favor UTF-8 encoding and character sets. However, any use of the straight single prime will not, strictly speaking, be an apostrophe, left single quote, or right single quote. It will be the straight single prime. If you don&#39;t care about that sort of thing, no worries. Most people won&#39;t notice or care.</p>
<p>Also, know the same is true for double prime (″), a left double quote (“), a right double quote (”), and the straight double prime (&quot;).</p>
<p>To cover the ground, we recommend explicitly setting the character set of your HTML documents:</p>
<pre><code class="language-html">&lt;!doctype html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
  &lt;/head&gt;
  &lt;body&gt;&lt;/body&gt;
&lt;/html&gt;
</code></pre>
<p>Many of the &quot;special characters&quot; you use will be rendered appropriately, or at least close to it, even if the browser doesn&#39;t have UTF-8 as the default character set.</p>
<h2 id="making-changes-can-be-annoying">Making changes can be annoying</h2>
<p>Making changes can quickly become annoying when it comes to static sites where each page is a standalone concept. (There&#39;s nothing wrong with having a single-age content-based site, either.)</p>
<p>It becomes annoying because you end up with a lot of &quot;boilerplate&quot;, which is just the same or dramatically similar code copied and pasted all over the place. Then, if you want to change something, like the copyright for your footer, you have to go through all the individual files and update the footer.</p>
<p>If you have 50 pages, changing one global piece of content becomes a 50-page change.</p>
<p>Some applications you can purchase allow you to create components and place them on pages to edit once to update everywhere. (My understanding is one such app for macOS is <a href="https://blocsapp.com">Blocs</a>.) You will build the site, then publish it using FTP (often built into the application).</p>
<p>Alternatively, if you&#39;re ready to increase complexity, you can use static site generators or a server-side rendering setup. Some static site generators include <a href="https://jekyllrb.com">Jekyll</a> (written in Ruby), <a href="https://www.11ty.dev">11ty</a> (written in JavaScript), <a href="https://jigsaw.tighten.com">Jigsaw</a> (written in PHP), and <a href="https://jamstack.org/generators/">many more</a>. Typically, you build the site on your local machine or within the hosting server. Then you&#39;ll run a script that will generate all the individual HTML files, similar to the applications in the previous paragraph, and make those available on your server in the &quot;public&quot; folder your domain points to. (You can even use <a href="https://pages.github.com">GitHub Pages</a> to host and server your site, there&#39;s some setup required, it uses Jekyll, and pushing to GitHub becomes your FTP.)</p>
<p>You can choose from several <a href="https://en.wikipedia.org/wiki/List_of_content_management_systems"><a href="CMSs">.content management systems</a> </a> if you want to increase complexity. The most prevalent is still <a href="https://wordpress.org">WordPress</a>, and many hosting service providers will set up a basic installation for you. These typically replace uploading files with separating content from layout through databases.</p>
<p>Or, what we typically find with Amos-style sites is we <a href="./roll-your-own/">roll our own</a>.</p>
    </body>
</html>
