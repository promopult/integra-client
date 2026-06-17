<?php

namespace Promopult\Integra;

final readonly class Credentials implements \Promopult\Integra\CredentialsInterface
{
    public function __construct(
        private string $hash,
        private string $cryptKey,
        private string $partnerPath = 'iframe',
        private string $apiHost = 'https://api.promopult.org',
    ) {
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getPartnerPath(): string
    {
        return  $this->partnerPath;
    }

    public function getCryptKey(): string
    {
        return $this->cryptKey;
    }

    public function getApiHost(): string
    {
        return $this->apiHost;
    }
}
