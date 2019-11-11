<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Interface CredentialsInterface
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
interface CredentialsInterface
{
    /**
     * Returns 32-symbol identity hash
     *
     * @return string
     */
    public function getHash(): string;

    /**
     * Returns crypt-key for data encode/decode
     *
     * @return string
     */
    public function getCryptKey(): string;

    /**
     * Partner path is a namespace of a custom partner methods.
     *
     * @return string
     */
    public function getPartnerPath(): string;

    /**
     * @return string
     */
    public function getApiHost(): string;
}
