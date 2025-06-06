<?php

namespace Promopult\Integra;

use GuzzleHttp\Psr7\Stream;

final class Request
{
    private string $method;
    private array $args;
    private CredentialsInterface $identity;
    private CryptInterface $crypt;
    private ?string $userHash;
    private ?array $queryParams;
    private ?array $post;

    public function __construct(
        string $method,
        array $args,
        CredentialsInterface $identity,
        CryptInterface $crypt,
        ?string $userHash,
        ?array $queryParams,
        ?array $post
    ) {
        $this->method = $method;
        $this->args = $args;
        $this->identity = $identity;
        $this->crypt = $crypt;
        $this->userHash = $userHash;
        $this->queryParams = $queryParams;
        $this->post = $post;
    }

    public function getCryptUrl(): string
    {
        $code = $this->crypt->encrypt(
            json_encode($this->args),
            $this->identity->getCryptKey()
        );

        $queryData = [
            'k' => 'zaa' . ($this->userHash ?? $this->identity->getHash()) . $code,
        ];

        if ($this->queryParams !== null) {
            $queryData = array_merge($queryData, $this->queryParams);
        }

        return sprintf(
            '%s/%s/%s?%s',
            $this->identity->getApiHost(),
            $this->identity->getPartnerPath(),
            $this->method,
            http_build_query($queryData)
        );
    }

    public function getPost(): ?string
    {
        if (empty($this->post)) {
            return null;
        }

        return json_encode($this->post);
    }
}
