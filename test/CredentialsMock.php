<?php

namespace Promopult\Integra\Test;

class CredentialsMock implements \Promopult\Integra\CredentialsInterface
{
    public function getHash(): string { return 'hash'; }
    public function getCryptKey(): string { return 'key'; }
    public function getPartnerPath(): string { return 'path'; }
    public function getApiHost(): string { return 'host'; }
}
