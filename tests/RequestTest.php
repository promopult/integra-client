<?php

namespace Promopult\Integra\Tests;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCryptUrl()
    {
        $request =  new \Promopult\Integra\Request(
            'hello',
            ['name' => 'name'],
            new \Promopult\Integra\Test\IdentityMock,
            new \Promopult\Integra\Test\CryptMock
        );

        $this->assertEquals('host/path/hello?k=zaahash%7B%22name%22%3A%22name%22%7D', $request->getCryptUrl());
    }
}
