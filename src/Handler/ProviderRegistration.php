<?php
declare(strict_types=1);

namespace DomainProviders\Handler;

use DomainProviders\Config\ProviderConfig;
use DomainProviders\Contract\DomainProviderInterface;

final class ProviderRegistration
{
    public function __construct(
        public readonly string $key,
        public readonly DomainProviderInterface $provider,
        public readonly ?ProviderConfig $config = null,
    ) {
    }
}
