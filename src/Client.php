<?php

namespace Promopult\Integra;

use Promopult\Integra\Exceptions\InvalidResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface as Psr7Request;
use Psr\Http\Message\ResponseInterface as Psr7Response;

/**
 * @method \Promopult\Integra\Response hello(array $data)
 * @method \Promopult\Integra\Response createUser(array $data)
 * @method \Promopult\Integra\Response cryptLogin(array $data)
 * @method \Promopult\Integra\Response archiveUser(array $data)
 * @method \Promopult\Integra\Response unarchiveUser(array $data)
 * @method \Promopult\Integra\Response doPayment(array $data)
 * @method \Promopult\Integra\Response confirmPayment(array $data)
 * @method \Promopult\Integra\Response declinePayment(array $data)
 * @method \Promopult\Integra\Response getUserData(array $data, string $userHash)
 * @method \Promopult\Integra\Response getUsersData(array $data)
 * @method \Promopult\Integra\Response getUserMessages(array $data, string $userHash)
 * @method \Promopult\Integra\Response getMessages(array $data)
 * @method \Promopult\Integra\Response getMessageTemplates(array $data)
 * @method \Promopult\Integra\Response readMessages(array $data)
 * @method \Promopult\Integra\Response changeUrl(array $data)
 * @method \Promopult\Integra\Response getFinSummaryByDate(array $data)
 * @method \Promopult\Integra\Response attachYandexMetrikaCounter(array $data)
 */
class Client implements \Promopult\Integra\TransportInterface
{
    protected CredentialsInterface $credentials;
    protected CryptInterface $crypt;
    protected ClientInterface $httpClient;
    protected ?Psr7Request $lastHttpRequest;
    protected ?Psr7Response $lastHttpResponse;

    public function __construct(
        CredentialsInterface $identity,
        CryptInterface $crypt,
        ClientInterface $httpClient
    ) {
        $this->credentials = $identity;
        $this->crypt = $crypt;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidResponseException
     */
    public function __call(string $methodName, array $ars = []): ResponseInterface
    {
        $request = new Request(
            $methodName,
            $ars[0] ?? [],
            $this->credentials,
            $this->crypt,
            $ars[1] ?? null
        );

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function send(\Promopult\Integra\RequestInterface $request): \Promopult\Integra\ResponseInterface
    {
        $httpRequest = new \GuzzleHttp\Psr7\Request('POST', $request->getCryptUrl(), [
            'Content-Type' => 'application/json',
        ]);

        $this->lastHttpRequest = $httpRequest;

        $httpResponse = $this->httpClient->sendRequest($httpRequest);

        $this->lastHttpResponse = $httpResponse;

        return \Promopult\Integra\Response::fromHttpResponse($httpResponse);
    }

    /***************/
    /* Debug stuff */
    /***************/

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getLastHttpResponse(): ?\Psr\Http\Message\ResponseInterface
    {
        return $this->lastHttpResponse;
    }

    /**
     * @return \Psr\Http\Message\RequestInterface|null
     */
    public function getLastHttpRequest(): ?\Psr\Http\Message\RequestInterface
    {
        return $this->lastHttpRequest;
    }

    public function getLastHttpResponseAsString(): string
    {
        if ($this->lastHttpResponse instanceof \Psr\Http\Message\ResponseInterface) {
            return \GuzzleHttp\Psr7\Message::toString($this->getLastHttpResponse());
        }

        return '';
    }

    public function getLastHttpRequestAsString(): string
    {
        if ($this->lastHttpRequest instanceof \Psr\Http\Message\RequestInterface) {
            return \GuzzleHttp\Psr7\Message::toString($this->getLastHttpRequest());
        }

        return '';
    }

    public function getCredentials(): CredentialsInterface
    {
        return $this->credentials;
    }
}
