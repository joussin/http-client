<?php

namespace Joussin\Component\HttpClient\Psr17;

use Joussin\Component\HttpClient\Psr7\Stream;
use Joussin\Component\HttpClient\Psr7\Uri;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{


    /**
     * Create a new URI.
     *
     * @param string $uri
     *
     * @return UriInterface
     *
     * @throws \InvalidArgumentException If the given URI cannot be parsed.
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }


}