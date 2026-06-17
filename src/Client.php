<?php

namespace Promopult\Integra;

use Promopult\Integra\Exceptions\InvalidResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\RequestInterface as Psr7RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
    protected ?Psr7RequestInterface $lastHttpRequest = null;
    protected ?Psr7ResponseInterface $lastHttpResponse = null;

    public function __construct(
        protected readonly CredentialsInterface $credentials,
        protected readonly CryptInterface $crypt,
        protected readonly ClientInterface $httpClient,
        protected readonly RequestFactoryInterface $requestFactory
    ) {
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
            return self::toString($this->getLastHttpResponse());
        }

        return '';
    }

    public function getLastHttpRequestAsString(): string
    {
        if ($this->lastHttpRequest instanceof \Psr\Http\Message\RequestInterface) {
            return self::toString($this->getLastHttpRequest());
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
        $httpRequest = $this->requestFactory
            ->createRequest('POST', $request->getCryptUrl())
            ->withHeader('Content-Type', 'application/json')
        ;

        $this->lastHttpRequest = $httpRequest;

        try {
            $httpResponse = $this->httpClient->sendRequest($httpRequest);
            $this->lastHttpResponse = $httpResponse;

            if ($httpResponse->getStatusCode() !== 200) {
                throw new InvalidResponseException(
                    $httpRequest,
                    $httpResponse
                );
            }

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

    protected static function toString(MessageInterface $message): string
    {
        if ($message instanceof RequestInterface) {
            $msg = trim($message->getMethod().' '
                    .$message->getRequestTarget())
                .' HTTP/'.$message->getProtocolVersion();
            if (!$message->hasHeader('host')) {
                $msg .= "\r\nHost: ".$message->getUri()->getHost();
            }
        } elseif ($message instanceof ResponseInterface) {
            $msg = 'HTTP/'.$message->getProtocolVersion().' '
                .$message->getStatusCode().' '
                .$message->getReasonPhrase();
        } else {
            throw new \InvalidArgumentException('Unknown message type');
        }

        foreach ($message->getHeaders() as $name => $values) {
            if (is_string($name) && strtolower($name) === 'set-cookie') {
                foreach ($values as $value) {
                    $msg .= "\r\n{$name}: ".$value;
                }
            } else {
                $msg .= "\r\n{$name}: ".implode(', ', $values);
            }
        }

        return "{$msg}\r\n\r\n".$message->getBody();
    }
}
