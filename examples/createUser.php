<?php

require_once '../vendor/autoload.php';

include 'my-credentials.php';

$client = new \Promopult\Integra\Client($identity, $crypt, $httpClient);

$response = $client->createUser([
    'username' => 'test01',
    'hash' => md5('test01'),
]);

echo (string)$response;