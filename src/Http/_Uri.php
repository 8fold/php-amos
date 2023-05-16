<?php
declare(strict_types=1);

namespace Eightfold\Amos\Http;

use Eightfold\Amos\Http\ServerRequestGet;

use Eightfold\Amos\Http\UriPath;

class Uri
{
    private UriPath $uriPath;

    public static function fromRequest(ServerRequestGet $request): self
    {
        return new self($request);
    }

    final private function __construct(
        private readonly ServerRequestGet $request
    ) {
    }

    public function path(): UriPath
    {
        if (isset($this->uriPath) === false) {
            $this->uriPath = UriPath::fromUri($this);
        }
        return $this->uriPath;
    }
}
