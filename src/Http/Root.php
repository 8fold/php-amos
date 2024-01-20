<?php
declare(strict_types=1);

namespace Eightfold\Amos\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

use Eightfold\Amos\Php\Interfaces\Falsifiable;
use Eightfold\Amos\Php\Interfaces\Stringable;

/**
 * @todo: Should we deprecate this class? It's effectively the same as one of
 *        the nyholm PSR-7 classes, I thnk. Does anyone actually us it?
 */
final class Root implements Falsifiable, Stringable
{
    private const SCHEMES = ['http' => 80, 'https' => 443];

    /**
     * @var array<string, int<0, 65535>|string>
     */
    private array $parsed_uri = [];

    private string $scheme;

    private string $host;

    private int|false $port;

    public static function fromRequest(RequestInterface $request): self
    {
        return self::fromUri($request->getUri());
    }

    public static function fromUri(UriInterface $uri): self
    {
        return self::fromString(strval($uri));
    }

    public static function fromString(string $uri): self
    {
        return new self($uri);
    }

    private function __construct(private readonly string $uri)
    {
        $this->setParsedUri();
    }

    public function getScheme(): string
    {
        if (isset($this->scheme)) {
            return $this->scheme;
        }

        if (array_key_exists('scheme', $this->parsed_uri) === false) {
            return '';
        }

        $scheme = $this->parsed_uri['scheme'];
        if (is_string($scheme) === false) {
            return '';
        }

        return $this->withScheme($scheme)->getScheme();
    }

    public function getAuthority(): string
    {
        $host = $this->getHost();
        if ($host === '') {
            return '';
        }

        $port = $this->getPort();

        if ($port !== false) {
            $port = ':' . $port;
        }

        return $host . $port;
    }

    public function getHost(): string
    {
        if (isset($this->host)) {
            return $this->host;
        }

        if (array_key_exists('host', $this->parsed_uri) === false) {
            return '';
        }

        $host = $this->parsed_uri['host'];
        if (is_string($host) === false) {
            return '';
        }

        return $this->withHost($host)->getHost();
    }

    public function getPort(): int|false
    {
        if (isset($this->port)) {
            return $this->port;
        }

        if (array_key_exists('port', $this->parsed_uri) === false) {
            return false;
        }

        return $this->withPort(
            intval($this->parsed_uri['port'])
        )->getPort();
    }

    private function withScheme(string $scheme): Root
    {
        $newScheme = strtr(
            $scheme,
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'abcdefghijklmnopqrstuvwxyz'
        );

        if (isset($this->scheme) and $this->scheme === $newScheme) {
            return $this;
        }

        $new = clone $this;
        $new->scheme = $newScheme;
        $new->port = $new->filterPort($new->getPort());

        return $new;
    }

    private function withHost(string $host): Root
    {
        $newHost = strtr(
            $host,
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'abcdefghijklmnopqrstuvwxyz'
        );

        if (isset($this->host) and $this->host === $newHost) {
            return $this;
        }

        $new = clone $this;
        $new->host = $newHost;

        return $new;
    }

    private function withPort(int|null $port): Root
    {
        if ($port === null) {
            $port = false;
        }
        if ($port === false or $port === $this->filterPort($port)) {
            $this->port = $port;
            return $this;
        }

        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    public function toString(): string
    {
        return $this->getScheme() . '://' . $this->getAuthority();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toBool(): bool
    {
        if ($this->getScheme() === '') {
            return false;

        } elseif ($this->getAuthority() === '') {
            return false;

        }
        return true;
    }

    public function isValid(): bool
    {
        return $this->toBool();
    }

    private function setParsedUri(): void
    {
        if (strlen($this->uri) > 0 and count($this->parsed_uri) === 0) {
            $p = parse_url($this->uri);
            if ($p === false) {
                $this->parsed_uri = [];

            } else {
                $this->parsed_uri = $p;

            }
        }
    }

    private function filterPort(int|false $port): int|false
    {
        if (0 > $port or 0xFFFF < $port) {
            return false;
        }

        if (self::isNonStandardPort($this->getScheme(), $port)) {
            return $port;
        }
        return false;
    }

    /**
     * Is a given port non-standard for the current scheme?
     */
    private static function isNonStandardPort(
        string $scheme,
        int|false $port
    ): bool {
        return !isset(self::SCHEMES[$scheme]) || $port !== self::SCHEMES[$scheme];
    }
}
