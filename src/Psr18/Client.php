<?php

namespace Joussin\Component\HttpClient\Psr18;

use Joussin\Component\HttpClient\Psr17\ServerRequestFactory;
use Psr\Http\Message\ServerRequestFactoryInterface;


class Client
{
    /**
     * @var ServerRequestFactoryInterface $serverRequestFactory
     */
    protected $serverRequestFactory;

    /**
     * @var ClientAdapter $clientAdapter
     */
    protected $clientAdapter;

    /**
     * @var string
     */
    protected $baseUri;

    public function __construct(string $baseUri = '', ClientAdapter $clientAdapter = null)
    {
        $this->setBaseUri($baseUri);
        $this->setClientAdapter($clientAdapter);

        $this->serverRequestFactory = new ServerRequestFactory();
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = str_ends_with($baseUri, '/') ? substr($baseUri,0, -1) : $baseUri;
    }

    public function getBaseUri(string $uri = '')
    {
        return $this->baseUri . $uri;
    }

    public function send(string $method, string $uri = '', array $parameters = [])
    {
        $request = $this->getServerRequestFactory()->createServerRequest($method, $this->getBaseUri($uri), $parameters);
        return $this->getClientAdapter()->sendRequest($request);
    }


    /**
     * @param ClientAdapter $clientAdapter
     */
    public function setClientAdapter(ClientAdapter $clientAdapter = null)
    {
        $this->clientAdapter = $clientAdapter ?? new CurlClientAdapter();
    }

    /**
     * @return ClientAdapter
     */
    public function getClientAdapter()
    {
        return $this->clientAdapter;
    }

    /**
     * @return ServerRequestFactoryInterface
     */
    public function getServerRequestFactory()
    {
        return $this->serverRequestFactory;
    }



}