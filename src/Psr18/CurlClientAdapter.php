<?php

namespace Joussin\Component\HttpClient\Psr18;

use Joussin\Component\HttpClient\Psr17\ResponseFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurlClientAdapter extends ClientAdapter
{
    protected $clientOperator = null;

    public function __construct()
    {
        $this->clientOperator = new CurlClient();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $params = $request->getMethod() == "GET" ? $request->getQueryParams() : $request->getParsedBody();
        $result = $this->clientOperator->request($request->getMethod(), $request->getUri()->full(), $params);

        $response = (new ResponseFactory())->createResponse($result['code']);
        $response->withBodyContent($result['content'] ?? []);

        return $response;
    }
}