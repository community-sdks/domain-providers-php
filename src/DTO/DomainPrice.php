<?php
declare(strict_types=1);

namespace DomainProviders\DTO;

final class DomainPrice
{
    public function __construct(
        public readonly string $currency,
        public readonly string $registrationPrice,
        public readonly string $renewalPrice,
        public readonly string $transferPrice,
        public readonly ?string $restorePrice,
        public readonly bool $premium,
        public readonly int $billingPeriodYears,
        public readonly ?string $providerReference = null,
    ) {
    }
}
