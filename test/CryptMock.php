<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra\Test;

class CryptMock implements \Promopult\Integra\CryptInterface
{
    public function encrypt(string $s, string $ck): string { return $s; }
    public function decrypt(string $s, string $ck): string { return $s; }
}
