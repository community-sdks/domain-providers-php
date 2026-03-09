<?php
declare(strict_types=1);

namespace DomainProviders\Provider\GoDaddy;

final class GoDaddyConfig
{
    public function __construct(
        public readonly string $apiKey,
        public readonly string $apiSecret,
        public readonly string $customerId,
        public readonly ?string $shopperId = null,
        public readonly string $environment = 'production',
        public readonly ?string $marketId = null,
    ) {
    }
}
