<?php

namespace Promopult\Integra\Test;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCryptUrl()
    {
        $request =  new \Promopult\Integra\Request(
            'hello',
            ['name' => 'name'],
            new \Promopult\Integra\Test\CredentialsMock,
            new \Promopult\Integra\Test\CryptMock,
            null,
            [],
            null
        );

        $this->assertEquals('host/path/hello?k=zaahash%7B%22name%22%3A%22name%22%7D', $request->getCryptUrl());
    }

    public function testGetCryptUrlWithUserHash()
    {
        $request =  new \Promopult\Integra\Request(
            'hello',
            ['name' => 'name'],
            new \Promopult\Integra\Test\CredentialsMock,
            new \Promopult\Integra\Test\CryptMock,
            'userhash',
            [],
            null
        );

        $this->assertEquals('host/path/hello?k=zaauserhash%7B%22name%22%3A%22name%22%7D', $request->getCryptUrl());
    }

    public function testGetCryptUrlWithUserHashAndQueryParams()
    {
        $request =  new \Promopult\Integra\Request(
            'hello',
            ['name' => 'name'],
            new \Promopult\Integra\Test\CredentialsMock,
            new \Promopult\Integra\Test\CryptMock,
            'userhash',
            ['p1' => 'abc'],
            null
        );

        $this->assertEquals(
            'host/path/hello?k=zaauserhash%7B%22name%22%3A%22name%22%7D&p1=abc',
            $request->getCryptUrl()
        );
    }

    public function testGetCryptUrlWithPost()
    {
        $request = new \Promopult\Integra\Request(
            'hello',
            ['name' => 'name'],
            new \Promopult\Integra\Test\CredentialsMock,
            new \Promopult\Integra\Test\CryptMock,
            'userhash',
            ['p1' => 'abc'],
            ['post' => 'data']
        );

        $this->assertEquals(
            'host/path/hello?k=zaauserhash%7B%22name%22%3A%22name%22%7D&p1=abc',
            $request->getCryptUrl()
        );
    }
}
