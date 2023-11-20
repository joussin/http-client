<?php

namespace Joussin\Component\HttpClient\Psr7;


use Joussin\Component\HttpClient\HttpDefinitions\HttpMessage;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    /**
     * @var string $version
     */
    protected $version = '1.1';
    /**
     * @var array $headers
     */
    protected $headers = [];

    /**
     * @var StreamInterface $body
     */
    protected $body;


    public function setHeaders($headers = [])
    {
        foreach ($headers as $header_key => $header_value)
            $this->withAddedHeader($header_key, $header_value);
    }


    public function setBody($body = [])
    {
        $parsedBody = is_array($body) && array_key_first($body) ? $body[array_key_first($body)] : null; // $body[HttpMessage::JSON] - $body[HttpMessage::FORM_PARAMS] - $body[HttpMessage::MULTIPART]
        $this->withParsedBody($parsedBody);
        $this->withBodyContent($this->getParsedBody());
    }


    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $this->version = $version;
        return $this;
    }


    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->headers[$name]);
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        if($this->hasHeader($name))
        {
            unset($this->headers[$name]);
        }
        return $this;
    }


    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->body = $body;
        return $this;
    }

    public function withBodyContent($content): MessageInterface
    {
        $content = is_scalar($content) ? $content : json_encode($content);
        $body = Stream::fromResource($content ?? '');
        $this->withBody($body);

        return $this;
    }
}