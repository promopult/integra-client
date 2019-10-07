<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Interface ResponseInterface
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
interface ResponseInterface
{
    /**
     * Returns version of API.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Returns API call error present flag
     *
     * @return bool
     */
    public function hasError(): bool;

    /**
     * Returns the ID of error.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Returns the error description.
     *
     * @return string
     */
    public function getStatusMessage(): string;

    /**
     * Returns the API notices. Like deprecated method or API version.
     *
     * @return string[]
     */
    public function getNotices(): array;

    /**
     * Returns API response payload.
     *
     * @return mixed
     */
    public function getData();
}
