<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class DomainInfo
{
    /**
     * @param list<string>|null $nameservers
     * @param list<string>|null $rawStatuses
     */
    public function __construct(
        public readonly string $domain,
        public readonly string $status,
        public readonly ?string $expirationDate = null,
        public readonly ?string $registrationDate = null,
        public readonly ?array $nameservers = null,
        public readonly ?bool $authCodeSupported = null,
        public readonly ?bool $locked = null,
        public readonly ?bool $privacyEnabled = null,
        public readonly ?string $providerReference = null,
        public readonly ?array $rawStatuses = null,
    ) {
    }
}
