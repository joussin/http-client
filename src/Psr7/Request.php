<?php

namespace Joussin\Component\HttpClient\Psr7;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message  implements RequestInterface
{
    /**
     * @var string $method
     */
    protected $method = 'GET';

    /**
     * @var Uri $uri
     */
    protected $uri;


    protected $requestTarget;


    public function __construct(string $method, $uri)
    {
        $this->withMethod(strtoupper($method));

        $uri = is_string($uri) ? new Uri($uri) : $uri;
        $this->withUri($uri);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $this->method = $method;
        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $this->uri = $uri;
        return $this;
    }


    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() != '') {
            $target .= '?'.$this->uri->getQuery();
        }

        return $target;
    }


    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new \InvalidArgumentException(
                'Invalid request target provided; cannot contain whitespace'
            );
        }

        $this->requestTarget = $requestTarget;

        return $this;
    }


}