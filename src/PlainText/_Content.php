<?php
declare(strict_types=1);

namespace Eightfold\Amos\PlainText;

use Stingable;
use StdClass;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

use Eightfold\Amos\SiteInterface;
use Eightfold\Amos\RealPaths\ForContentFiles;
use Eightfold\Amos\PlainText\FromPrimitives;

final class Content
{
    public static function replacePartialsInContentWithComponents(
        string $content,
        array $components,
        string $pattern = '{!!([\S\s]*?)!!}'
    ): string {

    }

    public static function partialsInContent(
        string $content,
        string $pattern = '{!!([\S\s]*?)!!}'
    ): StdClass {
        $matches = [];
        preg_match_all('/' . $pattern . '/', $content, $matches);

        $partials = new StdClass();
        if (count($matches) > 0) {
            $partials->to_replace = $matches[0];
            $partials->to_use     = [];

            $toUse = $matches[1];
            for ($i = 0; $i < count($partials->to_replace); $i++) {
                $partials->to_use[] = self::partialWithArgs($toUse[$i]);
            }
        }
        return $partials;
    }

    public static function partialWithArgs(
        string $fullReference
    ): StdClass {
        $pattern   = trim($fullReference);
        $reference = $pattern;
        $args      = new StdClass();
        if (str_contains($pattern, ':')) {
            list($reference, $a) = explode(':', $pattern, 2);
            $a = explode(',', $a);
            foreach ($a as $b) {
                list($key, $value) = explode('=', $b);
                $key   = trim($key);
                $value = trim($value);

                $args->{$key} = $value;
            }
        }

        $obj            = new StdClass();
        $obj->pattern   = $pattern;
        $obj->reference = $reference;
        $obj->args      = $args;

        return $obj;
    }

    public static function replace(
        string $reference,
        string $replacement,
        string $content
    ): string {
        return str_replace($reference, $replacement, $content);
    }

    public static function inPublicFileFromSite(
        SiteInterface $site,
        LoggerInterface|false $logger = false
    ): string {
        return self::inPublicFileFromRequest(
            $site->contentRoot(),
            $site->request(),
            $logger
        );
    }

    public static function inPublicFileFromRequest(
        string|Stringable $contentRoot,
        RequestInterface $request,
        LoggerInterface|false $logger = false
    ): string {
        return self::inPublicFileFromUri(
            $contentRoot,
            $request->getUri(),
            $logger
        );
    }

    public static function inPublicFileFromUri(
        string|Stringable $contentRoot,
        UriInterface $uri,
        LoggerInterface|false $logger = false
    ): string {
        return self::inPublicFile(
            $contentRoot,
            $uri->getPath(),
            $logger
        );
    }

    public static function fromSite(
        SiteInterface $site,
        string|Stringable $at = '',
        LoggerInterface|false $logger = false
    ): string {
        return self::inFile(
            $site->contentRoot(),
            $at,
            $logger
        );
    }

    public static function inPublicFile(
		string|Stringable $contentRoot,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): string {
        return FromPrimitives::inPublicFile(
            $contentRoot,
            ForContentFiles::filename(),
            $at,
            $logger
        );
    }

    public static function inFile(
		string|Stringable $contentRoot,
		string|Stringable $at = '',
		LoggerInterface|false $logger = false
    ): string {
        return FromPrimitives::inFile(
            $contentRoot,
            ForContentFiles::filename(),
            $at,
            $logger
        );
    }
}
