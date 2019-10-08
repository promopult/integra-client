<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra\Test;


class IdentityTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructor()
    {
        $identity = new \Promopult\Integra\Identity('hash', 'key', 'path', 'host');

        $this->assertEquals('hash', $identity->getHash());
        $this->assertEquals('key', $identity->getCryptKey());
        $this->assertEquals('path', $identity->getPartnerPath());
        $this->assertEquals('host', $identity->getApiHost());
    }
}
