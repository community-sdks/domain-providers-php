<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class AvailabilityResult
{
    public function __construct(
        public readonly bool $available,
        public readonly bool $premium,
        public readonly ?Money $price = null,
        public readonly ?string $reason = null,
        public readonly ?string $providerReference = null,
    ) {
    }
}
