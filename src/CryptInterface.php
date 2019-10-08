<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Interface CryptInterface
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
interface CryptInterface
{
    /**
     * @param string $string
     * @param string $key
     * @return string
     */
    public function encrypt(string $string, string $key): string;

    /**
     * @param string $string
     * @param string $key
     * @return string
     */
    public function decrypt(string $string, string $key): string;
}
