<?php

namespace Promopult\Integra;

interface TransportInterface
{
    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws
     */
    public function send(RequestInterface $request): ResponseInterface;
}
