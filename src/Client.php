<?php

namespace Promopult\Integra;

use Promopult\Integra\Exceptions\InvalidResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface as Psr7RequestInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

/**
 * @method \Promopult\Integra\Response hello(array $data)
 * @method \Promopult\Integra\Response createUser(array $data)
 * @method \Promopult\Integra\Response cryptLogin(array $data)
 * @method \Promopult\Integra\Response archiveUser(array $data)
 * @method \Promopult\Integra\Response unarchiveUser(array $data)
 * @method \Promopult\Integra\Response doPayment(array $data)
 * @method \Promopult\Integra\Response confirmPayment(array $data)
 * @method \Promopult\Integra\Response declinePayment(array $data)
 * @method \Promopult\Integra\Response getUserData(array $data)
 * @method \Promopult\Integra\Response getUsersData(array $data)
 * @method \Promopult\Integra\Response getUserMessages(array $data, string $userHash)
 * @method \Promopult\Integra\Response getMessages(array $data)
 * @method \Promopult\Integra\Response getMessageTemplates(array $data)
 * @method \Promopult\Integra\Response readMessages(array $data)
 * @method \Promopult\Integra\Response changeUrl(array $data)
 * @method \Promopult\Integra\Response getFinSummaryByDate(array $data)
 * @method \Promopult\Integra\Response attachYandexMetrikaCounter(array $data)
 */
class Client
{
    protected CredentialsInterface $credentials;
    protected CryptInterface $crypt;
    protected ClientInterface $httpClient;

    protected ?Psr7RequestInterface $lastHttpRequest = null;
    protected ?Psr7ResponseInterface $lastHttpResponse = null;

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
     * @throws \Throwable
     * @throws ClientExceptionInterface
     * @throws InvalidResponseException
     */
    public function request(
        string $methodName,
        array $data,
        ?string $userHash = null,
        ?array $queryParams = null,
        ?array $post = null
    ): \Promopult\Integra\Response {
        $request = new Request(
            $methodName,
            $data,
            $this->credentials,
            $this->crypt,
            $userHash,
            $queryParams,
            $post
        );

        return $this->send($request);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidResponseException
     * @throws \Throwable
     */
    public function __call(string $methodName, array $ars = []): \Promopult\Integra\Response
    {
        return $this->request(
            $methodName,
            $ars[0],         // data
            $ars[1] ?? null, // userHash
            $ars[2] ?? null, // queryParams
            $ars[3] ?? null  // post
        );
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

    /* Protected */

    /**
     * @throws \Throwable
     * @throws ClientExceptionInterface
     * @throws InvalidResponseException
     */
    protected function send(Request $request): Response
    {
        $httpRequest = new \GuzzleHttp\Psr7\Request(
            'POST',
            $request->getCryptUrl(),
            [
                'Content-Type' => 'application/json',
            ],
            $request->getPost()
        );

        $this->lastHttpRequest = $httpRequest;

        try {
            $httpResponse = $this->httpClient->sendRequest($httpRequest);
            $this->lastHttpResponse = $httpResponse;
            return Response::fromHttpResponse($httpResponse);
        } catch (\Throwable $e) {
            if (
                method_exists($e, 'getResponse')
                && $e->getResponse() instanceof \Psr\Http\Message\ResponseInterface
            ) {
                $this->lastHttpResponse = $e->getResponse();
            }
            throw $e;
        }
    }
}
