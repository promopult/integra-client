<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Interface RequestInterface
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
interface RequestInterface
{
    /**
     * @return string
     */
    public function getCryptUrl(): string;
}
