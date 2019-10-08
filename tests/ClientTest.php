<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra\Tests;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    // ToDo: network error tests
    // ToDo: incorrect response tests

    /**
     * @dataProvider methodDataProvider
     */
    public function testMethods(string $method, array $correctData, array $incorrectData): void
    {
        $client = $this->createClient();

        $correctResponse = $client->$method($correctData);

        $this->assertInstanceOf(\Promopult\Integra\Response::class, $correctResponse);

        // ToDo: check response data
    }

    private function createClient(): \Promopult\Integra\Client
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            // Correct API response
            new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                'version' => 1,
                'notices' => [],
                'status' => [
                    'code' => 0,
                    'message' => ''
                ],
                'error' => false
            ])),

            // ToDo: wrong response

            // ToDo: Network error
            //new \GuzzleHttp\Exception\RequestException("Error Communicating with Server", new \GuzzleHttp\Psr7\Request('GET', 'test'))
        ]);

        $httpClient = new \GuzzleHttp\Client([
            'handler' => \GuzzleHttp\HandlerStack::create($mock)
        ]);

        return new \Promopult\Integra\Client(
            new \Promopult\Integra\Tests\IdentityMock,
            new \Promopult\Integra\Tests\CryptMock,
            new \Http\Adapter\Guzzle6\Client($httpClient)
        );
    }


    public function methodDataProvider(): array
    {
        // methodName | correctParams | incorrectParams
        return [
            ['hello', [], []],
            ['createUser', [], []],
            ['cryptLogin', [], []],
            ['archiveUser', [], []],
            ['unarchiveUser', [], []],
            ['doPayment', [], []],
            ['confirmPayment', [], []],
            ['declinePayment', [], []],
            ['getUserData', [], []],
            ['getUsersData', [], []],
            ['getUserMessages', [], []],
            ['getMessages', [], []],
            ['getMessageTemplates', [], []],
            ['readMessages', [], []],
            ['changeUrl', [], []],
        ];
    }
}
