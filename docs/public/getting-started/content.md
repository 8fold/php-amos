# Getting started

One of the things I appreciate about the Internet is how easy, relatively speaking, it is to create something without any special hardware or software.

1. Plain text editor available for free on every operating system.
	1. macOS has [TextEdit](https://support.apple.com/guide/textedit/welcome/mac). You'll want to [change the format to plain text](https://support.apple.com/guide/textedit/change-textedit-settings-txted1063/1.17/mac/13.0).
	2. Windows has [Notepad](https://apps.microsoft.com/store/detail/windows-notepad/9MSMLRH6LZF3?hl=en-us&gl=us).
2. Open a new file and type "Hello, World!" on the first line.
3. Save the file as something with a ".html" extension.
	1. ex. "index.html"
4. Open that file in a web browser.
5. You should see "Hello, World!"

You can create an entire site on your computer (called "local"). There are some drawbacks here, most notably for our purposes, which is that if you want multiple paragraphs, you'll need some [.Hypertext Markup Language](HTML).

1. Open the file.
2. Change "Hello, World!" to `<p>Hello, World!</p>`.
	1. Add a second paragraph: `<p>How are you?</p>`.
3. Save the file.
4. Refresh your browser.

`<p>` means we want to start a paragraph and `</p>` is where we want that paragraph to end. HTML as a language ignores most whitespace beyond a single space.

Technically speaking, there are some other requirements, but this is enough to get started on the fundamental concepts of web development. If you'd like to continue expanding your HTML knowledge, I appreciate this [HTML for absolute beginners](https://html.com).

Fundamental concepts:

1. You don't need special hardware or software to get started.
2. The Internet is made of plain text; see fundamental concept number one.
3. Most people only need to know a few HTML tags to make something interesting.

## From local to global

If you want to journal on your local machine, many solutions allow you to do this without having it visible in a browser. Many of us want others to see what we post online; many solutions exist. Some of us who want to post online would rather do it ourselves; own our data and content.

This is where hosting comes into play.

A host is a computer running server software that allows others to find, connect, and make requests. This could be the computer you use daily, but many of us delegate and outsource that to someone else. There are many options, depending on what you're going for.

Fundamental concepts:

1. You'll usually register a human-friendly domain name (think phone number).
2. You'll point that human-friendly domain name to a publicly accessible [.Internet Protocol](IP) Address; not human-friendly (think [IMEI number](https://support.apple.com/en-us/HT204073)).
3. You'll somehow upload files and folders to the other computer (host).

Domain registration will cost around 20 [.United States Dollars](USD) per year. Hosting, the ability to put your files somewhere other people can access them, will cost a few dollars monthly. And in both cases, there are often discounts if you buy multiple years at a time.

I use [Dreamhost](https://www.dreamhost.com) and have since about 2005. I started on a [shared hosting plan](https://www.dreamhost.com/hosting/shared/), with multiple websites hosted on a single server. I moved to a [[.Virtual Private Server](VPS) plan](https://www.dreamhost.com/hosting/vps/) around 2018. (I actively avoid GoDaddy and Network Solutions.)

Most hosting providers offer control panels that will give you [.File Transfer Protocol](FTP) to the part of the server that you pay for. There are also standalone applications you can purchase. Your host should have [instructions on how to connect via FTP](https://help.dreamhost.com/hc/en-us/articles/360020552911-Logging-into-your-DreamPress-site-via-FTP).

I've used [Transmit by Panic](https://panic.com/transmit/) for years when I need to do FTP things. Before that, when I would do development at work, I'd use Notepad and upload files through the host's website. I have a different workflow and don't go through FTP too often.

When you upload pages, chances are they should be in their own folder and named `index.html`. You should be able to go to `http://yourdomain.com/` and see your main file. And anyone else on the Internet can do the same, at least by default.

## HTML is annoying, and typography is hard

HTML may be easy, relatively speaking. However, it's pretty annoying. It uses a lot of non-standard characters like the less-than sign, greater-than sign, and so on. We've created a lot of ways to simplify its creation.

Specialized text editors will autocomplete tags; I use [Panic Nova](https://nova.app) as of this writing. Before that, I used [Sublime Text](https://www.sublimetext.com). For the most part, I use a technology that started in 2004 and has been evolving ever since called [Markdown](https://daringfireball.net/projects/markdown/).

Amos-style sites tend to favor Markdown for the main content, and HTML and [.Cascading Stylesheets](CSS) for layout and aesthetics.

One of the things that makes HTML annoying is character encoding and the difference between primes, apostrophes, and opening and closing quotes.

A single prime (′) is not the same as an apostrophe (՚), a left single quote (‘), or a right single quote (’); however, we use the same key on our keyboards for all four. The software we use to see the text may or may not make our choices. Further, the plain text uses a different character, a straight single prime (\'). If the browser doesn't know the character set used, and we use any of these characters in the content could result in a missing character icon or something like this: Iâ€™m.

Amos-style sites tend to favor UTF-8 encoding and character sets. However, any use of the straight single prime will not, strictly speaking, be an apostrophe, left single quote, or right single quote. It will be the straight single prime. If you don't care about that sort of thing, no worries. Most people won't notice or care.

Also, know the same is true for double prime (″), a left double quote (“), a right double quote (”), and the straight double prime (\").

To cover the ground, we recommend explicitly setting the character set of your HTML documents:

```html
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body></body>
</html>
```

Many of the "special characters" you use will be rendered appropriately, or at least close to it, even if the browser doesn't have UTF-8 as the default character set.

## Making changes can be annoying

Making changes can quickly become annoying when it comes to static sites where each page is a standalone concept. (There's nothing wrong with having a single-age content-based site, either.)

It becomes annoying because you end up with a lot of "boilerplate", which is just the same or dramatically similar code copied and pasted all over the place. Then, if you want to change something, like the copyright for your footer, you have to go through all the individual files and update the footer.

If you have 50 pages, changing one global piece of content becomes a 50-page change.

Some applications you can purchase allow you to create components and place them on pages to edit once to update everywhere. (My understanding is one such app for macOS is [Blocs](https://blocsapp.com).) You will build the site, then publish it using FTP (often built into the application).

Alternatively, if you're ready to increase complexity, you can use static site generators or a server-side rendering setup. Some static site generators include [Jekyll](https://jekyllrb.com) (written in Ruby), [11ty](https://www.11ty.dev) (written in JavaScript), [Jigsaw](https://jigsaw.tighten.com) (written in PHP), and [many more](https://jamstack.org/generators/). Typically, you build the site on your local machine or within the hosting server. Then you'll run a script that will generate all the individual HTML files, similar to the applications in the previous paragraph, and make those available on your server in the "public" folder your domain points to. (You can even use [GitHub Pages](https://pages.github.com) to host and server your site, there's some setup required, it uses Jekyll, and pushing to GitHub becomes your FTP.)

You can choose from several [[.content management systems](CMSs) ](https://en.wikipedia.org/wiki/List_of_content_management_systems) if you want to increase complexity. The most prevalent is still [WordPress](https://wordpress.org), and many hosting service providers will set up a basic installation for you. These typically replace uploading files with separating content from layout through databases.

Or, what we typically find with Amos-style sites is we [roll our own](./roll-your-own/).
