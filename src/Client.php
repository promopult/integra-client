<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Class Client
 *
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
 * @method \Promopult\Integra\Response getUserMessages(array $data)
 * @method \Promopult\Integra\Response getMessages(array $data)
 * @method \Promopult\Integra\Response getMessageTemplates(array $data)
 * @method \Promopult\Integra\Response readMessages(array $data)
 * @method \Promopult\Integra\Response changeUrl(array $data)
 * @method \Promopult\Integra\Response getFinSummaryByDate(array $data)
 * @method \Promopult\Integra\Response attachYandexMetrikaCounter(array $data)
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
class Client implements \Promopult\Integra\TransportInterface
{
    /**
     * @var \Promopult\Integra\CredentialsInterface
     */
    protected $identity;

    /**
     * @var \Promopult\Integra\CryptInterface
     */
    protected $crypt;

    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $lastHttpRequest;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $lastHttpResponse;

    /**
     * Client constructor.
     *
     * @param \Promopult\Integra\CredentialsInterface $identity
     * @param \Promopult\Integra\CryptInterface $crypt
     * @param \Psr\Http\Client\ClientInterface $httpClient
     */
    public function __construct(
        \Promopult\Integra\CredentialsInterface $identity,
        \Promopult\Integra\CryptInterface $crypt,
        \Psr\Http\Client\ClientInterface $httpClient
    ) {
        $this->identity = $identity;
        $this->crypt = $crypt;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $methodName
     * @param array $ars
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function __call(string $methodName, array $ars = []): \Promopult\Integra\ResponseInterface
    {
        $request = new \Promopult\Integra\Request(
            $methodName,
            $ars[0] ?? [],
            $this->identity,
            $this->crypt
        );

        return $this->send($request);
    }

    /**
     * {@inheritDoc}
     */
    public function send(\Promopult\Integra\RequestInterface $request): \Promopult\Integra\ResponseInterface
    {
        $httpRequest = new \GuzzleHttp\Psr7\Request('POST', $request->getCryptUrl(), [
            'Content-Type' => 'application/json'
        ]);

        $this->lastHttpRequest = $httpRequest;

        $httpResponse = $this->getHttpClient()->sendRequest($httpRequest);

        $this->lastHttpResponse = $httpResponse;

        return \Promopult\Integra\Response::fromHttpResponse($httpResponse);
    }

    /**
     * @return \Psr\Http\Client\ClientInterface
     */
    protected function getHttpClient(): \Psr\Http\Client\ClientInterface
    {
        return $this->httpClient;
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

    /**
     * @return string
     */
    public function getLastHttpResponseAsString(): string
    {
        if ($this->lastHttpResponse instanceof \Psr\Http\Message\ResponseInterface) {
            return \GuzzleHttp\Psr7\str($this->getLastHttpResponse());
        }

        return '';
    }

    /**
     * @return string
     */
    public function getLastHttpRequestAsString(): string
    {
        if ($this->lastHttpRequest instanceof \Psr\Http\Message\RequestInterface) {
            return \GuzzleHttp\Psr7\str($this->getLastHttpRequest());
        }

        return '';
    }
}
