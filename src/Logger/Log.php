<?php
declare(strict_types=1);

namespace Eightfold\Amos\Logger;

use Stringable;
use JsonSerializable;

use StdClass;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Log implements Stringable, JsonSerializable
{
    private ResponseInterface|false $response = false;

    private ServerRequestInterface|false $request = false;

    public static function with(
        string $message,
        StdClass|false $extras = false,
        array $context = []
    ): self {
        return new self($message, $extras, $context);
    }

    final private function __construct(
        private string $message,
        private StdClass|false $extras,
        private array $context
    ) {
    }

    public function includeResponse(ResponseInterface $response): self
    {
        if ($this->response === false) {
            $this->response = $response;
        }
        return $this;
    }

    public function includeRequest(ServerRequestInterface $request): self
    {
        if ($this->request === false) {
            $this->request = $request;
        }
        return $this;
    }

    private function message(): string
    {
        return $this->message;
    }

    private function extras(): StdClass|false
    {
        return $this->extras;
    }

    private function response(): ResponseInterface|false
    {
        return $this->response;
    }

    private function request(): ServerRequestInterface|false
    {
        return $this->request;
    }

    private function context(): array
    {
        return $this->context;
    }

    private function compiledMessage(
        string|Stringable $message,
        array $context = []
    ): string {
        $replace = [];
        foreach ($context as $token => $content) {
            if (
                is_string($content) or
                (is_object($content) and method_exists($content, '__toString'))
            ) {
                $replace['{' . $token . '}'] = $content;
            }
        }
        return str_replace(
            array_keys($replace),
            array_values($replace),
            $message
        );
    }

    /** JsonSerializable **/
    public function jsonSerialize(): mixed
    {
        $obj = new StdClass();

        $obj->message = $this->compiledMessage(
            $this->message(),
            $this->context()
        );

        if ($this->response()) {
            $response = $this->response();

            $obj->response              = new StdClass();
            $obj->response->status_code = $response->getStatusCode();
            $obj->response->headers     = $response->getHeaders();
            $obj->response->content     = $response->getBody()->getContents();

        }

        if ($this->request()) {
            $request = $this->request();

            $obj->request           = new StdClass();
            $obj->request->headers  = $request->getHeaders();
            $obj->request->content  = $request->getBody()->getContents();

        }

        if ($this->extras()) {
            $obj->extras = $this->extras();
        }

        return $obj;
    }

    /** Stringable **/
    public function __toString(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}
