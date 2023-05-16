<?php
declare(strict_types=1);

namespace Eightfold\Amos\Http;

use Psr\Http\Message\ServerRequestInterface;

use Nyholm\Psr7\Factory\Psr17Factory;

use Nyholm\Psr7Server\ServerRequestCreator;

use Eightfold\Amos\Http\Uri;

class ServerRequestGet
{
    private Uri $uri;

    public static function usingDefault(): self
    {
        $psr17Factory = new Psr17Factory();

        $requestCreator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $request = $requestCreator->fromGlobals();

        return self::fromPsr7($request);
    }

    public static function fromPsr7(ServerRequestInterface $request): self
    {
        return new self($request);
    }

    final private function __construct(
        private readonly ServerRequestInterface $request
    ) {
    }

    public function uri(): Uri
    {
        if (isset($this->uri) === false) {
            $this->uri = Uri::fromRequest($this);
        }
        return $this->uri;
    }
}
