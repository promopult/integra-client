<?php

require_once '../vendor/autoload.php';

$client = new \Promopult\Integra\Client(
    new \Promopult\Integra\Credentials(getenv('__HASH__'), getenv('__CRYPT_KEY__')),
    new \AcmeCrypt(),
    new \GuzzleHttp\Client(),
    new \GuzzleHttp\Psr7\HttpFactory()
);

$response = $client->getUserData(
    [
        'partner' => getenv('__HASH__')
    ],
    getenv('__USER_HASH__')
);

var_dump($response->getData());