<?php

namespace Promopult\Integra;

final class Request
{
    private string $method;
    private array $args;
    private CredentialsInterface $identity;
    private CryptInterface $crypt;
    private ?string $userHash;
    private ?array $queryParams;

    public function __construct(
        string $method,
        array $args,
        CredentialsInterface $identity,
        CryptInterface $crypt,
        ?string $userHash,
        array $queryParams
    ) {
        $this->method = $method;
        $this->args = $args;
        $this->identity = $identity;
        $this->crypt = $crypt;
        $this->userHash = $userHash;
        $this->queryParams = $queryParams;
    }

    public function getCryptUrl(): string
    {
        $code = $this->crypt->encrypt(
            json_encode($this->args),
            $this->identity->getCryptKey()
        );

        $queryData = array_merge(
            [
                'k' => 'zaa' . ($this->userHash ?? $this->identity->getHash()) . $code
            ],
            $this->queryParams
        );

        return sprintf(
            '%s/%s/%s?%s',
            $this->identity->getApiHost(),
            $this->identity->getPartnerPath(),
            $this->method,
            http_build_query($queryData)
        );
    }
}
