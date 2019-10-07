<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra\Test;

class CryptMock implements \Promopult\Integra\CryptInterface
{
    public function encode(string $s, string $ck): string { return $s; }
    public function decode(string $s, string $ck): string { return $s; }
}
