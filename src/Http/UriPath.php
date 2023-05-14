<?php
declare(strict_types=1);

namespace Eightfold\Amos\Http;

use Eightfold\Amos\Http\ServerRequestGet;
use Eightfold\Amos\Htpp\Uri;

class UriPath
{
    public static function fromPsr7(ServerRequestGet $request): self
    {
        return new fromUri($request->uri());
    }

    public static function fromUri(Uri $uri): self
    {

    }

    public static function fromString(string $path): self
    {
        return new self($path)
    }

    final private function __construct(
        private readonly string $path
    ) {
    }
}
