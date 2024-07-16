<?php

namespace Promopult\Integra;

final class Request implements \Promopult\Integra\RequestInterface
{
    private const PARAM_NAME = 'k';
    private const PARAM_VALUE_PREFIX = 'zaa';

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $args;

    /**
     * @var CredentialsInterface
     */
    private $identity;

    /**
     * @var CryptInterface
     */
    private $crypt;

    /**
     * @var ?string
     */
    private $userHash;

    /**
     * Request constructor.
     *
     * @param string $method
     * @param array $args
     * @param CredentialsInterface $identity
     * @param CryptInterface $crypt
     */
    public function __construct(
        string $method,
        array $args,
        CredentialsInterface $identity,
        CryptInterface $crypt,
        ?string $userHash = null
    ) {
        $this->method = $method;
        $this->args = $args;
        $this->identity = $identity;
        $this->crypt = $crypt;
        $this->userHash = $userHash;
    }

    /**
     * {@inheritDoc}
     */
    public function getCryptUrl($user_hash = null): string
    {
        return sprintf(
            '%s/%s/%s?%s=%s%s%s',
            $this->identity->getApiHost(),
            $this->identity->getPartnerPath(),
            $this->method,
            self::PARAM_NAME,
            self::PARAM_VALUE_PREFIX,
            ($this->userHash ?? $this->identity->getHash()),
            urlencode($this->crypt->encrypt(json_encode($this->args), $this->identity->getCryptKey()))
        );
    }
}
