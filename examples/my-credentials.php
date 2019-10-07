<?php

/* Экземпляр класса идентификации  */

$identity = new class implements \Promopult\Integra\IdentityInterface {
    public function getHash(): string { return getenv('__HASH__'); }
    public function getCryptKey(): string { return getenv('__CRYPT_KEY__'); }
    public function getPartnerPath(): string { return getenv('__PARTNER_PATH__'); }
    public function getApiHost(): string { return getenv('__API_HOST__'); }
};

/* Экземпляр класса реализующий шифрование данных */

$crypt = new class implements \Promopult\Integra\CryptInterface {
    public function encode(string $s, string $ck): string {
        $r = '';
        for ($i = 0; $i < strlen($s); $i++) {
            $r .= chr(ord(substr($s, $i, 1)) + ord(substr($ck, ($i % strlen($ck)) - 1, 1)));
        }
        return base64_encode($r);
    }
    public function decode(string $string, string $key): string { return ''; }
};
