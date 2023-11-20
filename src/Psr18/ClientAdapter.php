<?php

namespace Joussin\Component\HttpClient\Psr18;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ClientAdapter implements ClientInterface
{
    abstract public function sendRequest(RequestInterface $request): ResponseInterface;
}