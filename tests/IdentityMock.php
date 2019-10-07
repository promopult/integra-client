<?php

namespace Promopult\Integra\Test;

class IdentityMock implements \Promopult\Integra\IdentityInterface
{
    public function getHash(): string { return 'hash'; }
    public function getCryptKey(): string { return 'key'; }
    public function getPartnerPath(): string { return 'path'; }
    public function getApiHost(): string { return 'host'; }
}
