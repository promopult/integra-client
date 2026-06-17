<?php

namespace Promopult\Integra;

use Promopult\Integra\Exceptions\InvalidResponseException;

final readonly class Response
{
    public function __construct(
        private string $version,
        private bool $hasError,
        private int $statusCode,
        private string $statusMessage,
        private array $notices,
        private mixed $data,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public static function fromHttpResponse(\Psr\Http\Message\ResponseInterface $response): self
    {
        $parsedBody = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);

        return new self(
            (string) $parsedBody['version'],
            (bool) $parsedBody['error'],
            (int) $parsedBody['status']['code'],
            (string) $parsedBody['status']['message'],
            (array) $parsedBody['notices'],
            $parsedBody['data'] ?? null
        );
    }

    public function __toString(): string
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

    public function getVersion(): string
    {
        return $this->version;
    }

    public function hasError(): bool
    {
        return $this->hasError;
    }

    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getNotices(): array
    {
        return $this->notices;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
