# Promopult Integra API PHP client library

Клиентская библиотека для сервиса [promopult.org](https://promopult.org).

[![Build Status](https://travis-ci.org/promopult/integra-client.svg?branch=master)](https://travis-ci.org/promopult/integra-client)

### Установка 

```bash
$ composer require promopult/integra-client
```
или 
```php
"require": {
  ...
  "promopult/integra-client": "*"
  ...
}
```

### Примеры
Смотрите папку [examples](/examples).

```php
$client = new \Promopult\Integra\Client($identity, $crypt);

$response = $client->hello([
    'name' => 'Dmitry'
]);

var_dump($response->getData()); // string(14) "Hello, Dmitry!"
```