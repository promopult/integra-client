<?php

namespace Promopult\Integra\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class InvalidResponseException extends IntegraException
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $message = 'Invalid response received',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->request = $request;
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}