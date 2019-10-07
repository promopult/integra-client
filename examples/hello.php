<?php

require_once '../vendor/autoload.php';

include 'my-credentials.php';

$client = new \Promopult\Integra\Client($identity, $crypt);

$response = $client->hello([
    'name' => 'Dmitry',
]);

var_dump($response->getData());