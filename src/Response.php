<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Class Response
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
final class Response implements \Promopult\Integra\ResponseInterface
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var bool
     */
    private $hasError;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $statusMessage;

    /**
     * @var array
     */
    private $notices;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return static
     */
    public static function fromHttpResponse(\Psr\Http\Message\ResponseInterface $response) : self
    {
        $bodyData = json_decode($response->getBody()->getContents(), true);

        return new static(
            (string)$bodyData['version'],
            (bool)$bodyData['error'],
            (int)$bodyData['status']['code'],
            (string)$bodyData['status']['message'],
            (array)$bodyData['notices'],
            $bodyData['data'] ?? null
        );
    }

    public function __construct(
        string $version,
        bool $hasError,
        int $statusCode,
        string $statusMessage,
        array $notices,
        $data
    ) {
        $this->version = $version;
        $this->hasError = $hasError;
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
        $this->notices = $notices;
        $this->data = $data;
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode([
            'version' => $this->getVersion(),
            'notices' => $this->getNotices(),
            'status' => [
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ],
            'error' => $this->hasError(),
            'data' => $this->getData(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * {@inheritDoc}
     */
    public function hasError(): bool
    {
        return $this->hasError;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritDoc}
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
