<?php

namespace Promopult\Integra;

interface CredentialsInterface
{
    /**
     * Returns 32-symbol identity hash
     */
    public function getHash(): string;

    /**
     * Returns crypt-key for data encode/decode
     */
    public function getCryptKey(): string;

    /**
     * Partner path is a namespace of the custom partner methods.
     */
    public function getPartnerPath(): string;

    /**
     * Retrieves the API host URL as a string.
     */
    public function getApiHost(): string;
}
