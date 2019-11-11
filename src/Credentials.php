<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Class Identity
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
final class Credentials implements \Promopult\Integra\CredentialsInterface
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $cryptKey;

    /**
     * @var string
     */
    private $partnerPath;

    /**
     * @var string
     */
    private $apiHost;

    /**
     * Identity constructor.
     *
     * @param string $hash
     * @param string $cryptKey
     * @param string $partnerPath
     * @param string $apiHost
     */
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
