<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

use Promopult\Integra\Exceptions\InvalidResponseException;

/**
 * Class Response
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
final class Response implements \Promopult\Integra\ResponseInterface
{
    private string $version;
    private bool $hasError;
    private int $statusCode;
    private string $statusMessage;
    private array $notices;

    /**
     * @var mixed
     */
    private $data;

    public static function fromHttpResponse(\Psr\Http\Message\ResponseInterface $response): self
    {
        $parsedBody = json_decode($response->getBody()->__toString(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidResponseException(json_last_error_msg());
        }

        return new self(
            (string) $parsedBody['version'],
            (bool) $parsedBody['error'],
            (int) $parsedBody['status']['code'],
            (string) $parsedBody['status']['message'],
            (array) $parsedBody['notices'],
            $parsedBody['data'] ?? null
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
