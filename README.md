# Promopult Integra API PHP client library

Клиентская библиотека для сервиса [promopult.org](https://promopult.org).

[![Build Status](https://travis-ci.org/promopult/integra-client.svg?branch=master)](https://travis-ci.org/promopult/integra-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/promopult/integra-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/promopult/integra-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/promopult/integra-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/promopult/integra-client/?branch=master)

### Установка 

```bash
$ composer require promopult/integra-client
```
или 
```php
"require": {
  ...
  "promopult/integra-client": "~2.0"
  ...
}
```

### Примеры
Смотрите папку [examples](/examples).

```php
$client = new \Promopult\Integra\Client(
    new \Promopult\Integra\Credentials(getenv('__HASH__'), getenv('__KEY__')), 
    new \AcmeCrypt(),
    new \GuzzleHttp\Client()
);

$response = $client->hello([
    'name' => 'Dmitry'
]);

var_dump($response->getData()); // string(14) "Hello, Dmitry!"
```