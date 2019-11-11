<?php

$identity = new \Promopult\Integra\Credentials(
    getenv('__HASH__'),
    getenv('__CRYPT_KEY__'),
    getenv('__PARTNER_PATH__'),
    getenv('__API_HOST__')
);

$crypt = new class implements \Promopult\Integra\CryptInterface {
    public function encrypt(string $s, string $ck): string {
        $r = '';
        for ($i = 0; $i < strlen($s); $i++) {
            $r .= chr(ord(substr($s, $i, 1)) + ord(substr($ck, ($i % strlen($ck)) - 1, 1)));
        }
        return base64_encode($r);
    }
    public function decrypt(string $string, string $key): string { return ''; }
};
