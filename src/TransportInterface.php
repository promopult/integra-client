<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Interface TransportInterface
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
interface TransportInterface
{
    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws
     */
    public function send(RequestInterface $request): ResponseInterface;
}
