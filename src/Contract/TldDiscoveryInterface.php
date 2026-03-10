<?php
declare(strict_types=1);

namespace DomainProviders\Contract;

interface TldDiscoveryInterface
{
    /** @return list<string> */
    public function listSupportedTlds(): array;
}
