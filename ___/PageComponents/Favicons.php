<?php
declare(strict_types=1);

namespace Eightfold\Amos\PageComponents;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Eightfold\HTMLBuilder\Element;

/**
 * We use https://realfavicongenerator.net to generate favicon-related assets.
 * We presume the names of these assets will not be changed.
 */
class Favicons implements Buildable
{
    /**
     *
     * @param string $themeColor For clients that recognize theme-color.
     * @param string $path Where the assets live. The generator recommends the
              root directory, however, allows you to put a path prefix; use the
              same for both.
     * @param string $msAppTileColor For Microsoft Windows; prefer Metro colors.
     *        Will use themeColor, if not set.
     * @param string $safariTabColor For the Safari browser when tab is pinned.
     *        Will use themeColor, if not set.
     *
     * @return self
     */
    public static function create(
        string $themeColor,
        string $path = '',
        string $msAppTileColor = '',
        string $safariTabColor = ''
    ): self {
        return new self($path, $themeColor, $msAppTileColor, $safariTabColor);
    }

    final private function __construct(
        private string $path,
        private string $themeColor,
        private string $msAppTileColor = '',
        private string $safariTabColor = ''
    ) {
    }

    private function path(): string
    {
        return $this->path;
    }

    private function themeColor(): string
    {
        return $this->themeColor;
    }

    private function msAppTileColor(): string
    {
        if (strlen($this->msAppTileColor) === 0) {
            return $this->themeColor();
        }
        return $this->msAppTileColor;
    }

    private function safariTabColor(): string
    {
        if (strlen($this->safariTabColor) === 0) {
            return $this->themeColor();
        }
        return $this->safariTabColor;
    }

    public function build(): string
    {
        return Element::link()->omitEndTag()->props(
            'rel apple-touch-icon',
            'sizes 180x180',
            'href ' . $this->path() . '/apple-touch-icon.png'
        )->build()
        . Element::link()->omitEndTag()->props(
            'rel icon',
            'type image/png',
            'sizes 32x32',
            'href ' . $this->path() . '/favicon-32x32.png'
        )->build()
        . Element::link()->omitEndTag()->props(
            'rel icon',
            'type image/png',
            'sizes 16x16',
            'href ' . $this->path() . '/favicon-16x16.png'
        )->build()
        . Element::link()->omitEndTag()->props(
            'rel manifest',
            'href ' . $this->path() . '/site.webmanifest'
        )->build()
        . Element::link()->omitEndTag()->props(
            'rel mask-icon',
            'href ' . $this->path() . '/safari-pinned-tab.svg',
            'color ' . $this->safariTabColor()
        )->build()
        . Element::link()->omitendTag()->props(
            'rel shortcut icon',
            'href ' . $this->path() . '/favicon.ico'
        )->build()
        . Element::meta()->omitEndTag()->props(
            'name msapplication-TileColor',
            'content ' . $this->msAppTileColor()
        )->build()
        . Element::meta()->omitEndTag()->props(
            'name msapplication-config',
            'content ' . $this->path() . '/browserconfig.xml'
        )
        . Element::meta()->omitEndTag()->props(
            'name theme-color',
            'color ' . $this->themeColor()
        )->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
