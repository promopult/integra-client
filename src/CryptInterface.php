<?php

namespace Promopult\Integra;

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
