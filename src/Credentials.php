<?php

namespace Promopult\Integra;

final class Credentials implements \Promopult\Integra\CredentialsInterface
{
    private string $hash;
    private string $cryptKey;
    private string $partnerPath;
    private string $apiHost;

    public function __construct(
        string $hash,
        string $cryptKey,
        string $partnerPath = 'iframe',
        string $apiHost = 'https://api.promopult.org'
    ) {
        $this->hash = $hash;
        $this->cryptKey = $cryptKey;
        $this->partnerPath = $partnerPath;
        $this->apiHost = $apiHost;
    }

    /**
     * {@inheritDoc}
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerPath(): string
    {
        return  $this->partnerPath;
    }

    /**
     * {@inheritDoc}
     */
    public function getCryptKey(): string
    {
        return $this->cryptKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getApiHost(): string
    {
        return $this->apiHost;
    }
}
