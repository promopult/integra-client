<?php

namespace Promopult\Integra\Test;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testFromHttpResponse()
    {
        $httpResp = new \GuzzleHttp\Psr7\Response(
            200,
            [],
            json_encode([
                'version' => '1',
                'status'  => [
                    'code'    => 1,
                    'message' => 'message'
                ],
                'error'   => true,
                'notices' => ['notice'],
            ])
        );

        $apiResp = \Promopult\Integra\Response::fromHttpResponse($httpResp);

        $this->assertEquals('1', $apiResp->getVersion());
        $this->assertEquals(1, $apiResp->getStatusCode());
        $this->assertEquals('message', $apiResp->getStatusMessage());
        $this->assertEquals(true, $apiResp->hasError());
    }
}
