<?php
/**
 * @project Promopult Integra client library
 */

namespace Promopult\Integra;

/**
 * Class Request
 *
 * @author Dmitry Gladyshev <dgladyshev@promopult.ru>
 * @since 1.0
 */
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
        CryptInterface $crypt
    ) {
        $this->method = $method;
        $this->args = $args;
        $this->identity = $identity;
        $this->crypt = $crypt;
    }

    /**
     * {@inheritDoc}
     */
    public function getCryptUrl(): string
    {
        return $this->identity->getApiHost() . '/'
            . $this->identity->getPartnerPath() . '/'
            . $this->method . '?'
            . self::PARAM_NAME . '=' . self::PARAM_VALUE_PREFIX
            . $this->identity->getHash()
            . urlencode($this->crypt->encrypt(json_encode($this->args), $this->identity->getCryptKey()))
        ;
    }
}
