<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class ProviderMetadata
{
    /**
     * @param list<string>|null $supportedTlds
     * @param list<ProviderCapability> $capabilitySummary
     */
    public function __construct(
        public readonly string $providerName,
        public readonly string $providerKey,
        public readonly string $environment,
        public readonly ?string $accountReference,
        public readonly ?array $supportedTlds,
        public readonly array $capabilitySummary,
    ) {
    }
}
