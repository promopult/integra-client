<?php

namespace Promopult\Integra;

final readonly class Request
{
    public function __construct(
        private string $method,
        private array $args,
        private CredentialsInterface $identity,
        private CryptInterface $crypt,
        private ?string $userHash,
        private ?array $queryParams,
        private ?array $post,
    ) {
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
