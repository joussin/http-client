<?php

namespace Joussin\Component\HttpClient\Psr7;

use Joussin\Component\HttpClient\HttpDefinitions\HttpMessage;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends Request implements \Psr\Http\Message\ServerRequestInterface
{

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $cookieParams = [];

    /**
     * @var array|object|null
     */
    private $parsedBody;

    /**
     * @var array
     */
    private $queryParams = [];


    /**
     * @var array
     */
    private $uploadedFiles = [];

    /**
     * @var array
     */
    private $serverParams = [];


    public function __construct(string $method, $uri, array $serverParams = [])
    {
        parent::__construct($method, $uri);
        $this->serverParams = $serverParams;

        $this->setHeaders($serverParams[HttpMessage::HEADERS] ?? []);
        $this->setBody($serverParams[HttpMessage::BODY] ?? []);
        $this->withQueryParams($serverParams[HttpMessage::QUERY] ?? []);
    }



    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * @return array|object|null
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * @return array
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }




    public function withCookieParams(array $cookies): ServerRequestInterface
    {

        $this->cookieParams = $cookies;

        return $this;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {

        $this->queryParams = $query;

        return $this;
    }



    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {

        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }


    public function withParsedBody($data): ServerRequestInterface
    {

        $this->parsedBody = $data;

        return $this;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {

        $this->attributes[$name] = $value;

        return $this;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        if(!array_key_exists($name, $this->attributes))
        {
            return $this;
        }


        unset($this->attributes[$name]);


        return $this;
    }
}