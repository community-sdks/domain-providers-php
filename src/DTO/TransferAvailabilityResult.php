<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class TransferAvailabilityResult
{
    /** @param list<string>|null $reasons */
    public function __construct(
        public readonly string $transferStatus,
        public readonly ?bool $locked = null,
        public readonly ?bool $authCodeRequired = null,
        public readonly ?array $reasons = null,
        public readonly ?string $providerReference = null,
    ) {
    }
}
