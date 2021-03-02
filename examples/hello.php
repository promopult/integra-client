<?php

require_once '../vendor/autoload.php';

$client = new \Promopult\Integra\Client(
    new \Promopult\Integra\Credentials(getenv('__HASH__'), getenv('__CRYPT_KEY__')),
    new \AcmeCrypt(),
    new \GuzzleHttp\Client
);

$response = $client->hello([
    'name' => 'Dmitry',
]);

var_dump($response->getData());