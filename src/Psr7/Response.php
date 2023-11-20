<?php

namespace Joussin\Component\HttpClient\Psr7;



use Psr\Http\Message\ResponseInterface;


class Response extends Message implements ResponseInterface
{

    /**
     * @var int $statusCode
     */
    protected $statusCode = 200;

    /**
     * @var string $reasonPhrase
     */
    protected $reasonPhrase = '';



    public function __construct( int $code = 200, string $reasonPhrase = '')
    {
        $this->withStatus($code, $reasonPhrase);
    }


    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}