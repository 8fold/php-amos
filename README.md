# 8fold Amos for PHP

Amos is a set of helper classes and tools for a methodology of managing content and websites. By itself, Amos won't do it for you; however, Amos can be integrated into more robust frameworks and platforms.

## Installation

{how does one install the product}

## Usage

The base of Amos is the separation between content and the application. The URI is used as the bridge between those two worlds.

The following is a sample folder structure taken from a site leveraging the Amos methdology:

```bash
.
├── .alerts/
├── .assets/
│   ├── favicons/
│   └── ui/
├── .banners/
├── .media/
│   └── poster.png
├── .navigation/
├── .resources/
└── content.md
```

## Details

I (Josh) have developed multiple applications designed to help people easily update websites. I've also used a lot of content management systems and frameworks such as Laravel, WordPress, and some that I can't remember at the moment. Over the years I've decided to try and compartmentalize things a bit more. For example, use flat files for content, use front matter for details related to that content, and only use a database to manage relationships between content and metadata (if I never have to interact with a database again it will be too soon).

Over the years I've found myself writing similar code and solutions to similar problems on a semi-regular basis (if I never write another page titling method it will be too soon).

I also noticed that much of the code I was writing for these systems was in the administration area for the site: registration, sign-in, sending emails related to that, user permissions, and so on. With Amos, I feel like I can seek alternatives and automations that free me from having to deal with all that.

## Other

{links or descriptions or license, versioning, and governance}
