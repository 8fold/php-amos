<?php

declare(strict_types=1);

namespace Eigthfold\Amos\Http;

use Psr\Http\Message\ResponseInterface;

use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter as PsrEmitter;

class Emitter
{
    public static function emit(ResponseInterface $response): void
    {
        (new PsrEmitter())->emit($response);
    }
}
